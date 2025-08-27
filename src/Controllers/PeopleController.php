<?php
namespace App\Controllers;

use App\Models\AuthorModel;
use App\Utils\Response;

class PeopleController {
    private AuthorModel $authorModel;
    public function __construct() { global $db; $this->authorModel = new AuthorModel($db); }

    public function handle(string $method): void {
        switch ($method) {
            case 'GET':
                $params = param_get_lower();
                Response::json($this->authorModel->getAuthors($params));
                break;
            case 'POST':
                $data = json_body();
                if (!isset($data['name']) || trim((string)$data['name']) === '') {
                    Response::json(["error" => "Missing parameter: name"], 400);
                    return;
                }
                $this->authorModel->createAuthor($data['name']);
                Response::json(["message" => "Person created"], 201);
                break;
            case 'PATCH':
                $data = json_body();
                if (!isset($data['person_id'], $data['name'])) {
                    Response::json(["error" => "Missing parameters"], 400);
                    return;
                }
                $this->authorModel->updateAuthor((int)$data['person_id'], (string)$data['name']);
                Response::json(["message" => "Person updated"]);
                break;
            case 'DELETE':
                $data = json_body();
                if (!isset($data['person_id'])) {
                    Response::json(["error" => "Missing parameter: person_id"], 400);
                    return;
                }
                $this->authorModel->deleteAuthor((int)$data['person_id']);
                Response::json(["message" => "Person deleted"]);
                break;
            case 'OPTIONS':
                http_response_code(204);
                break;
            default:
                Response::json(["error" => "Method not allowed"], 405);
        }
    }
}