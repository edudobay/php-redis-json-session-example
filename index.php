<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/MySessionHandler.php';

if ($_GET['server']) { print_r($_SERVER); die; }
if ($_GET['env']) { print_r($_ENV); die; }

$redis_server = $_ENV['REDIS_SERVER'] ?? 'localhost:9001';

ini_set('session.serialize_handler', 'php_serialize');
ini_set('session.name', 'my_session'); // cookie name
ini_set('session.save_path', "tcp://$redis_server?prefix=session:");
ini_set('session.cookie_lifetime', 8640000); // 100 days
ini_set('session.cookie_httponly', true);
// ini_set('session.cookie_secure', true);

session_set_save_handler(new MySessionHandler(), true);

session_start();

if ($_GET['countMe'] ?? false) {
    $count = intval($_SESSION['count'] ?? 0);
    $_SESSION['count'] = ++$count;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'count' => $_SESSION['count'] ?? null,
]);
