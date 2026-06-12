<?php

require_once __DIR__ . '/env.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'smart_library';

$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/../'));
$docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
$basePath = '/';

if ($docRoot && strpos($projectRoot, $docRoot) === 0) {
    $basePath = substr($projectRoot, strlen($docRoot));
    $basePath = '/' . trim($basePath, '/');

    if ($basePath === '/') {
        $basePath = '/';
    }
}

if ($basePath !== '/' && substr($basePath, -1) !== '/') {
    $basePath .= '/';
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath);
}

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}