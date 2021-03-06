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

/**
 * Retrieve the list of user choices from the database as HTML.
 * @return {string} - A list of user choices as HTML.
 */
function createUserOptions()
{
    $users   = getUsers();
    $options = '';
    foreach ($users as $user) {
        $options .= "<option value='{$user['id']}'>{$user['display_name']}</option>";
    }
    return $options;
}

/**
 * Output the log to the specified file.
 * @param {string} $message - Log contents.
 */
function logOutput(string $message): void
{
    $log = date('Y-m-d H:i:s') . ' ' . $message . "\n";
    error_log($log, 3, __DIR__ . '/../logs/how_is_going_' . date('Y-m-d') . '.log');
}

/**
 * Retrieve a list of users from the database.
 * @return {Array} - A list of display user names.
 */
function getUsers(): array
{
    global $DB;
    $users = $DB->query('SELECT * FROM users')->fetchAll();
    return $users;
}

/**
 * Retrieves a list of tasks for the currently selected user from the database.
 * @pram {int} $user_id - The user ID.
 * @return {Array} - A list of tasks.
 */
function getTodayTasks(int $user_id, ?string $target_date = null): array
{
    global $DB;
    $today         = date('Y-m-d');
    $target_date ??= $today;
    $day           = explode('-', $target_date);
    $date          = checkdate(intval($day[1]), intval($day[2]), intval($day[0])) ? $target_date : $today;

    $sql = "SELECT * FROM tasks WHERE user_id = $user_id AND created_at = date('$date', 'localtime') AND deleted_at IS NULL";

    $tasks = $DB->query($sql)->fetchAll();
    return $tasks;
}
