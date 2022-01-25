<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");

require_once(__DIR__ . '/../../../lib/global_variables.php');
require_once(__DIR__ . '/../../../lib/functions.php');

$json = file_get_contents("php://input");
log_output('api/v1/save: ' . var_export($json, true));

$contents = json_decode($json, true);
log_output('api/v1/save: ' . var_export($contents, true));

$user_id = $contents['user_id'];
log_output('api/v1/save: user_id = ' . $user_id);
if (empty($user_id)) {
    log_output('api/v1/save: user_id is empty');
    http_response_code(400);
    return;
}

$todo_list = array_filter(explode("\n", $contents['todo']));
log_output('api/v1/save: todo_list = ' . var_export($todo_list, true));
if (empty($todo_list)) {
    log_output('api/v1/save: todo list is empty');
    http_response_code(400);
    return;
}

$DB->exec('BEGIN');

try {
    foreach ($todo_list as $value) {
        $sql    = "INSERT INTO tasks (user_id, task, status, created_at) VALUES (?, ?, 0, date('now', 'localtime'))";
        $params = [$user_id, trim($value)];
        log_output('api/v1/save: ' . var_export([$sql, $params], true));

        $DB->prepare($sql)->execute($params);
    }
    
    $DB->exec('COMMIT');
} catch (Exception $ex) {
    $DB->exec('ROLLBACK');
    log_output('api/v1/save: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "500",
        "message" => $ex->getMessage()
    ]);
    return;
}

http_response_code(200);
echo '{}';
