<?php
declare(strict_types=1);

require_once(__DIR__ . '/../lib/global_variables.php');

$DB->prepare("CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    created_at date NOT NULL DEFAULT (date('now', 'localtime')),
    deleted_at date,
    user_id TEXT NOT NULL,
    task TEXT NOT NULL,
    comment TEXT,
    status INTEGER NOT NULL default 0
)")->execute();

$DB->prepare("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    created_at date NOT NULL DEFAULT (date('now', 'localtime')),
    deleted_at date,
    display_name TEXT NOT NULL, -- is user name
    password TEXT NOT NULL
)")->execute();

echo "Database created.\n";


$sql_add_user = <<<SQL
INSERT INTO users (display_name, password) VALUES
    ('Chris', ''),
    ('Pat', ''),
    ('Alex', ''),
    ('Dana', '')
SQL;
$DB->exec($sql_add_user);
echo "Users added.\n";
