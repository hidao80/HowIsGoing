<?php
declare(strict_types=1);

/**
 * Multilingual phrase conversion
 * @param string $keyword
 * @return string
 */
function translation(string $keyword): string
{
    global $LANG;
    $dictonary = json_decode(file_get_contents(__DIR__ . "/../lang/$LANG.json"), true);
    return $dictonary[$keyword];
}

function get_user_list(): array
{
    global $DB;
    $users = $DB->query('SELECT * FROM users')->fetchAll();
    return $users;
}

function create_user_options()
{
    $users   = get_user_list();
    $options = '';
    foreach ($users as $user) {
        $options .= "<option value='{$user['id']}'>{$user['display_name']}</option>";
    }
    return $options;
}

function log_output(string $message): void
{
    $log = date('Y-m-d H:i:s') . ' ' . $message . "\n";
    error_log($log, 3, __DIR__ . '/../logs/how_is_going_' . date('Y-m-d') . '.log');
}

function getUsers(): array
{
    global $DB;
    $users = $DB->query('SELECT * FROM users')->fetchAll();
    return $users;
}

function getTodayTasks(int $user_id): array
{
    global $DB;
    $sql   = "SELECT * FROM tasks WHERE user_id = $user_id AND created_at = date('now', 'localtime')";
    $tasks = $DB->query($sql)->fetchAll();
    return $tasks;
}
