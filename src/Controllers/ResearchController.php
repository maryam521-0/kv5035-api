<?php
namespace App\Controllers;

use App\Models\ResearchModel;
use App\Utils\Response;

class ResearchController {
    private ResearchModel $researchModel;
    public function __construct() { global $db; $this->researchModel = new ResearchModel($db); }

    public function handle(string $method): void {
        switch ($method) {
            case 'GET':
                $params = param_get_lower();
                Response::json($this->researchModel->getResearch($params));
                break;
            case 'POST':
                $data = json_body();
                // Award give/remove
                if (isset($data['research_id'], $data['award_id'], $data['action'])) {
                    $rid = (int)$data['research_id']; $aid = (int)$data['award_id'];
                    if ($data['action'] === 'give') {
                        $this->researchModel->giveAward($rid, $aid);
                        Response::json(["message" => "Award added"]);
                        return;
                    } elseif ($data['action'] === 'remove') {
                        $this->researchModel->removeAward($rid, $aid);
                        Response::json(["message" => "Award removed"]);
                        return;
                    } else {
                        Response::json(["error" => "Invalid action (use 'give' or 'remove')"], 400);
                        return;
                    }
                }
                // Type change
                if (isset($data['research_id'], $data['type_id'])) {
                    $this->researchModel->changeType((int)$data['research_id'], (int)$data['type_id']);
                    Response::json(["message" => "Type updated"]);
                    return;
                }
                Response::json(["error" => "Missing parameters"], 400);
                break;
            case 'OPTIONS':
                http_response_code(204);
                break;
            default:
                Response::json(["error" => "Method not allowed"], 405);
        }
    }
}