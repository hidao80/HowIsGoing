<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/global_variables.php');
require_once(__DIR__ . '/../../../lib/functions.php');

$json = file_get_contents("php://input");
log_output(var_export($json, true));

// Only accept requests from the same host
if ($_SERVER['REMOTE_ADDR'] !== gethostbyname('localhost')) {
    http_response_code(403);
    echo json_encode([
        "status" => "403",
        "message" => 'Not the same host'
    ]);
    return;
}

$contents = json_decode($json, true);
log_output(var_export($contents, true));

$user_id = $contents['user_id'];
$content = $contents['tasks']['content'];

$DB->exec('BEGIN');

try {
    foreach ($content as $task) {
        $sql    = "UPDATE tasks SET status = ?, comment = ? WHERE id = ?";
        $params = [$task['status'], $task['comment'], $task['id']];
        log_output(var_export([$sql, $params], true));
        
        $DB->prepare($sql)->execute($params);
    }
    
    $DB->exec('COMMIT');
} catch (Exception $ex) {
    $DB->exec('ROLLBACK');
    log_output($ex->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "500",
        "message" => $ex->getMessage()
    ]);
    return;
}

http_response_code(200);
echo '';
