<?php

use CodeIgniter\I18n\Time;

if (!function_exists('humanize_time')) {
    function humanize_time($datetime)
    {
        if (empty($datetime)) {
            return '';
        }

        try {
            return Time::parse($datetime)->humanize();
        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        // Calculate weeks separately (DO NOT attach to $diff)
        $weeks = floor($diff->d / 7);
        $days = $diff->d % 7;

        $string = [
            'y' => ['value' => $diff->y, 'label' => 'year'],
            'm' => ['value' => $diff->m, 'label' => 'month'],
            'w' => ['value' => $weeks, 'label' => 'week'],
            'd' => ['value' => $days, 'label' => 'day'],
            'h' => ['value' => $diff->h, 'label' => 'hour'],
            'i' => ['value' => $diff->i, 'label' => 'minute'],
            's' => ['value' => $diff->s, 'label' => 'second'],
        ];

        $result = [];

        foreach ($string as $unit) {
            if ($unit['value']) {
                $result[] = $unit['value'] . ' ' . $unit['label'] . ($unit['value'] > 1 ? 's' : '');
            }
        }

        if (!$full) {
            $result = array_slice($result, 0, 1);
        }

        return $result ? implode(', ', $result) . ' ago' : 'just now';
    }
}