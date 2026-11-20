<?php
// Isolated real SQLite integration test; deliberately does not load the local .env.
define('ENVIRONMENT', 'testing');
define('HOMEPATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
define('CONFIGPATH', HOMEPATH . 'app/Config/');
define('FCPATH', HOMEPATH);
require CONFIGPATH . 'Paths.php';
require HOMEPATH . 'vendor/codeigniter4/framework/system/Boot.php';
class AccountTestBoot extends CodeIgniter\Boot {
    protected static function loadDotEnv(Config\Paths $paths): void {}
    public static function start(): void {
        $paths = new Config\Paths();
        static::definePathConstants($paths);
        static::bootTest($paths);
    }
}
AccountTestBoot::start();