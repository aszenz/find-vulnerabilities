<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Gounsch\Files;
use Gounsch\View;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

error_reporting(E_ALL);

session_start();

$_GET['action'] ??= 'index';
// VULN 1: http://127.0.0.1:9876/?user=eena to access any user's files
$user = $_GET['user'] ?? $_SESSION['user'] ?? null;

$dotEnv = new Dotenv();
$dotEnv->bootEnv(dirname(__DIR__).'/.env');

$dsnParser = new DsnParser();
$connection = DriverManager::getConnection($dsnParser->parse($_SERVER['DATABASE_URL']));

$files = new Files($connection, $user);
$view = new View($user);

$files->setup();

return [
    'files' => $files,
    'view' => $view,
];