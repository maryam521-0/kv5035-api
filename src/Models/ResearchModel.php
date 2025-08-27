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
        $sql = "SELECT DISTINCT r.research_id AS research_id, r.title,
                       COALESCE(r.abstract, '') AS abstract,
                       t.name AS type,
                       COALESCE(r.award_name, '') AS award
                FROM research r
                JOIN type t ON r.type_id = t.type_id
                LEFT JOIN research_has_author rha ON rha.research_id = r.research_id
                LEFT JOIN author au ON au.author_id = rha.author_id
                WHERE 1=1";
        $qp = [];

        if (!empty($params['research_id'])) {
            $sql .= " AND r.research_id = :rid";
            $qp[':rid'] = (int)$params['research_id'];
        }

        if (!empty($params['person_id'])) {
            $sql .= " AND au.author_id = :pid";
            $qp[':pid'] = (int)$params['person_id'];
        }

        if (!empty($params['search'])) {
            $sql .= " AND (LOWER(r.title) LIKE :search OR LOWER(r.abstract) LIKE :search)";
            $qp[':search'] = '%' . strtolower((string)$params['search']) . '%';
        }

        $sql .= " ORDER BY r.research_id ASC";

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
        // Update research table with award information
        $st = $this->db->prepare("UPDATE research SET award = :aid, award_name = (SELECT name FROM award WHERE id = :aid) WHERE research_id = :rid");
        $st->execute([':rid' => $researchId, ':aid' => $awardId]);
    }

    public function removeAward(int $researchId, int $awardId): void {
        // Remove award from research table
        $st = $this->db->prepare("UPDATE research SET award = NULL, award_name = NULL WHERE research_id = :rid");
        $st->execute([':rid' => $researchId]);
    }

    public function changeType(int $researchId, int $typeId): void {
        $st = $this->db->prepare("UPDATE research SET type_id = :tid WHERE research_id = :rid");
        $st->execute([':rid' => $researchId, ':tid' => $typeId]);
    }
}