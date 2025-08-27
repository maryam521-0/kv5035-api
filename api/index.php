<?php
require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\AboutController;
use App\Controllers\PeopleController;
use App\Controllers\ResearchController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$path = strtolower(trim(strtok($uri, '?'), '/'));

// Basic router - support both Nuwebspace and direct deployment paths
switch ($path) {
    case 'kv5035/coursework/api/about':
    case 'about':
        $controller = new AboutController();
        $controller->handle($method);
        break;
    case 'kv5035/coursework/api/people':
    case 'people':
        $controller = new PeopleController();
        $controller->handle($method);
        break;
    case 'kv5035/coursework/api/research':
    case 'research':
        $controller = new ResearchController();
        $controller->handle($method);
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found", "path" => $path]);
        break;
}