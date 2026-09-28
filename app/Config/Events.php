<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

/**
 * Internal Opportunistic Automation Runner (Pseudo-Cron)
 * Runs scheduled automations after the page is sent to the client,
 * so the platform never totally depends on external cron services.
 */
Events::on('post_system', static function (): void {
    if (is_cli()) {
        return;
    }

    if (env('internal_cron_enabled', true) === false || env('internal_cron_enabled', true) === 'false') {
        return;
    }

    $lockKey  = 'jobber_internal_cron_last_run';
    $interval = (int) env('internal_cron_interval', 900); // Every 15 minutes by default

    try {
        $cache = service('cache');
        $lastRun = $cache->get($lockKey);
        $now = time();

        if ($lastRun && ($now - (int)$lastRun) < $interval) {
            return;
        }

        // Lock for interval
        $cache->save($lockKey, $now, $interval);

        // Disconnect browser client so user experiences zero delay
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        @set_time_limit(120);
        @ignore_user_abort(true);

        $token = env('cron_token') ?: env('CRON_TOKEN', 'jobber_cron_secret_123');

        $request = service('request');
        $response = service('response');
        $logger = service('logger');

        $_GET['token'] = $token;

        $cron = new \App\Controllers\CronController();
        $cron->initController($request, $response, $logger);
        $cron->runAllAutomations();
    } catch (\Throwable $e) {
        log_message('error', 'Internal Pseudo-Cron Error: ' . $e->getMessage());
    }
});

