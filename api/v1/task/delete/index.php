<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");

require_once(__DIR__ . '/../../../../lib/global_variables.php');
require_once(__DIR__ . '/../../../../lib/functions.php');

// Read the JSON sent to you.
$json = file_get_contents("php://input");
logOutput('api/v1/task/delete: ' . var_export($json, true));

$contents = json_decode($json, true);
logOutput('api/v1/task/delete: ' . var_export($contents, true));

// If there is no user_id in the JSON sent make an error
// with status code 400 and abort the process.
$user_id = $contents['user_id'];
logOutput('api/v1/task/delete: user_id = ' . $user_id);
if (empty($user_id)) {
    logOutput('api/v1/task/delete: user_id is empty');
    http_response_code(400);
    return;
}

// If there is no  task id in the JSON sent
// the process is aborted with an error with status code 400.
$task_id = $contents['task_id'];
logOutput('api/v1/task/delete: task_id = ' . $task_id);
if (empty($task_id)) {
    logOutput('api/v1/task/delete: task_id is empty');
    http_response_code(400);
    return;
}

$DB->exec('BEGIN');

try {
    $sql    = "UPDATE tasks SET deleted_at = date('now', 'localtime') WHERE id = ? AND user_id = ?";
    $params = [$task_id, $user_id];
    logOutput('api/v1/task/delete: ' . var_export([$sql, $params], true));

    $DB->prepare($sql)->execute($params);
    
    $DB->exec('COMMIT');
} catch (Exception $ex) {
    // If the update fails, it will roll back and return status code 500.
    $DB->exec('ROLLBACK');
    logOutput('api/v1/task/delete: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "500",
        "message" => $ex->getMessage()
    ]);
    return;
}

http_response_code(200);
echo '{}';
