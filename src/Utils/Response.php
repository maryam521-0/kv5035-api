<?php
namespace App\Utils;

class Response {
    public static function json($data, int $code = 200): void {
        http_response_code($code);
        echo json_encode($data);
    }
}