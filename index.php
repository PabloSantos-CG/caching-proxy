<?php

require_once __DIR__ . '/vendor/autoload.php';

App\Utils\RequestFilter::filter();

use App\Core\Core;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$core = new Core();
$core->run();
