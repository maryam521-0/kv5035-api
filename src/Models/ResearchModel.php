<?php
namespace App\Models;

use PDO;

class ResearchModel {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    /**
     * Supports params: research_id, person_id, search, page
     */
    public function getResearch(array $params): array {
        $sql = "SELECT DISTINCT c.id AS research_id, c.title,
                       COALESCE(c.abstract, '') AS abstract,
                       t.name AS type,
                       COALESCE(a.name, '') AS award
                FROM content c
                JOIN type t ON c.type_id = t.id
                LEFT JOIN content_has_award ca ON c.id = ca.content_id
                LEFT JOIN award a ON ca.award_id = a.id
                LEFT JOIN content_has_author cha ON cha.content_id = c.id
                LEFT JOIN author au ON au.id = cha.author_id
                WHERE 1=1";
        $qp = [];

        if (!empty($params['research_id'])) {
            $sql .= " AND c.id = :rid";
            $qp[':rid'] = (int)$params['research_id'];
        }

        if (!empty($params['person_id'])) {
            $sql .= " AND au.id = :pid";
            $qp[':pid'] = (int)$params['person_id'];
        }

        if (!empty($params['search'])) {
            $sql .= " AND (LOWER(c.title) LIKE :search OR LOWER(c.abstract) LIKE :search)";
            $qp[':search'] = '%' . strtolower((string)$params['search']) . '%';
        }

        $sql .= " ORDER BY c.id ASC";

        $limit = 10;
        if (!empty($params['page']) && is_numeric($params['page']) && (int)$params['page'] > 0) {
            $offset = ((int)$params['page'] - 1) * $limit;
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $st = $this->db->prepare($sql);
        $st->execute($qp);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function giveAward(int $researchId, int $awardId): void {
        $st = $this->db->prepare("INSERT INTO content_has_award (content_id, award_id) VALUES (:rid, :aid)");
        $st->execute([':rid' => $researchId, ':aid' => $awardId]);
    }

    public function removeAward(int $researchId, int $awardId): void {
        $st = $this->db->prepare("DELETE FROM content_has_award WHERE content_id = :rid AND award_id = :aid");
        $st->execute([':rid' => $researchId, ':aid' => $awardId]);
    }

    public function changeType(int $researchId, int $typeId): void {
        $st = $this->db->prepare("UPDATE content SET type_id = :tid WHERE id = :rid");
        $st->execute([':rid' => $researchId, ':tid' => $typeId]);
    }
}