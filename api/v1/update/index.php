<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");

require_once(__DIR__ . '/../../../lib/global_variables.php');
require_once(__DIR__ . '/../../../lib/functions.php');

$json = file_get_contents("php://input");
log_output('api/v1/update: json = ' . var_export($json, true));

$contents = json_decode($json, true);
log_output('api/v1/update: contents = ' . var_export($contents, true));

$user_id = $contents['user_id'];
log_output('api/v1/update: user_id = ' . $user_id);
if (empty($user_id)) {
    log_output('api/v1/save: user_id is empty');
    http_response_code(400);
    return;
}

$content = $contents['tasks']['content'];
log_output('api/v1/save: tasks content = ' . var_export($content, true));
if (empty($content)) {
    log_output('api/v1/save: tasks content is empty');
    http_response_code(400);
    return;
}

$DB->exec('BEGIN');

try {
    foreach ($content as $task) {
        $sql    = "UPDATE tasks SET status = ?, comment = ? WHERE id = ?";
        $params = [$task['status'], $task['comment'], $task['id']];
        log_output('api/v1/update: ' . var_export([$sql, $params], true));
        
        $DB->prepare($sql)->execute($params);
    }
    
    $DB->exec('COMMIT');
} catch (Exception $ex) {
    $DB->exec('ROLLBACK');
    log_output('api/v1/update: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "500",
        "message" => $ex->getMessage()
    ]);
    return;
}

http_response_code(200);
echo '{}';
