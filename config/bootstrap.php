<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

header('Content-Type: application/json');

$headers = getallheaders();
$apiKey = $headers['x-api-key'] ?? $_GET['api_key'] ?? '';
$EXPECTED_KEY = 'w23042229-key';

if ($apiKey !== $EXPECTED_KEY) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized - Invalid API key"]);
    exit;
}

try {
    $GLOBALS['db'] = new PDO('sqlite:' . __DIR__ . '/../database/hri2023.sqlite');
    $GLOBALS['db']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Utility functions
function param_get_lower(): array {
    return array_change_key_case($_GET, CASE_LOWER);
}

function json_body(): array {
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?? [];
}
