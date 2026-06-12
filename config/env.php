<?php

$envPath = dirname(__DIR__) . '/.env';

if (!file_exists($envPath)) {
    error_log('ENV loader: .env file not found at ' . $envPath);
    return;
}

$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $line = trim($line);

    if ($line === '' || $line[0] === '#') {
        continue;
    }

    if (strpos($line, '=') === false) {
        error_log('ENV loader: invalid line skipped: ' . $line);
        continue;
    }

    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);

    if (strlen($value) >= 2 && ($value[0] === '"' && substr($value, -1) === '"')) {
        $value = stripslashes(substr($value, 1, -1));
    }

    if (strlen($value) >= 2 && ($value[0] === "'" && substr($value, -1) === "'")) {
        $value = substr($value, 1, -1);
    }

    if (getenv($name) === false) {
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}
