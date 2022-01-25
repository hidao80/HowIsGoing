<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");

require_once(__DIR__ . '/../../../lib/global_variables.php');
require_once(__DIR__ . '/../../../lib/functions.php');

// Read the JSON sent to you.
$json = file_get_contents("php://input");
logOutput('api/v1/update: json = ' . var_export($json, true));

$contents = json_decode($json, true);
logOutput('api/v1/update: contents = ' . var_export($contents, true));

// If there is no user_id in the JSON sent, make an error
// with status code 400 and abort the process.
$user_id = $contents['user_id'];
logOutput('api/v1/update: user_id = ' . $user_id);
if (empty($user_id)) {
    logOutput('api/v1/save: user_id is empty');
    http_response_code(400);
    return;
}

// If there is no list of tasks in the sent JSON,
// make an error. Process is aborted with status code 400.
$content = $contents['tasks']['content'];
logOutput('api/v1/save: tasks content = ' . var_export($content, true));
if (empty($content)) {
    logOutput('api/v1/save: tasks content is empty');
    http_response_code(400);
    return;
}

$DB->exec('BEGIN');

try {
    foreach ($content as $task) {
        $sql    = "UPDATE tasks SET status = ?, comment = ? WHERE id = ?";
        $params = [$task['status'], $task['comment'], $task['id']];
        logOutput('api/v1/update: ' . var_export([$sql, $params], true));
        
        $DB->prepare($sql)->execute($params);
    }
    
    $DB->exec('COMMIT');
} catch (Exception $ex) {
    // If the update fails, it will roll back and status error code 500.
    $DB->exec('ROLLBACK');
    logOutput('api/v1/update: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "500",
        "message" => $ex->getMessage()
    ]);
    return;
}

http_response_code(200);
echo '{}';
