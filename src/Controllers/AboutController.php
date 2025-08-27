<?php
namespace App\Controllers;

class AboutController {
    public function handle($method) {
        if ($method === 'GET') {
            echo json_encode([
                "student_id" => "w23042229",
                "degree_programme" => "BSc Computer Science",
                "full_name" => "Zafer Ahmad",
                "module_code" => "KV5035"
            ]);
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
        }
    }
}
