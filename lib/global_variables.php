<?php
declare(strict_types=1);

// Change the display language according to the browser locale
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $LANG = substr(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0], 0, 2) ?? 'en';
}

// Using todo databases with Sqlite3
$DB = new PDO('sqlite:' . __DIR__ . '/../db/tasks.db');
