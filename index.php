<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Predis\Client as PredisClient;
use App\Core\Core;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (php_sapi_name() === 'cli' && in_array('--clear', $_SERVER['argv'])) {
    $predisClient = new PredisClient([
        'host' => '127.0.0.1',
        'port' => $_ENV['REDIS_PORT'],
        'password' => $_ENV['REDIS_PASSWORD'],
    ]);

    $predisClient->flushall();
    exit();
}

App\Utils\RequestFilter::filter();

$core = new Core();
$core->run();
