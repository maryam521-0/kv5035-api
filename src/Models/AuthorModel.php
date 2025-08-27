<?php
namespace App\Models;

use PDO;

class AuthorModel {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }

    /**
     * Supports params: person_id, research_id, search, page
     */
    public function getAuthors(array $params): array {
        $sql = "SELECT DISTINCT a.id AS person_id, a.name
                FROM author a
                LEFT JOIN author_content cha ON cha.author_id = a.id
                LEFT JOIN content c ON c.id = cha.content_id
                WHERE 1=1";
        $qp = [];

        if (!empty($params['person_id'])) {
            $sql .= " AND a.id = :person_id";
            $qp[':person_id'] = (int)$params['person_id'];
        }

        if (!empty($params['research_id'])) {
            $sql .= " AND c.id = :research_id";
            $qp[':research_id'] = (int)$params['research_id'];
        }

        if (!empty($params['search'])) {
            $sql .= " AND LOWER(a.name) LIKE :search";
            $qp[':search'] = '%' . strtolower((string)$params['search']) . '%';
        }

        $sql .= " ORDER BY a.id ASC";

        // Pagination (10 per page)
        $limit = 10;
        if (!empty($params['page']) && is_numeric($params['page']) && (int)$params['page'] > 0) {
            $offset = ((int)$params['page'] - 1) * $limit;
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $st = $this->db->prepare($sql);
        $st->execute($qp);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAuthor(string $name): void {
        $st = $this->db->prepare("INSERT INTO author (name) VALUES (:name)");
        $st->execute([':name' => trim($name)]);
    }

    public function updateAuthor(int $id, string $name): void {
        $st = $this->db->prepare("UPDATE author SET name = :name WHERE id = :id");
        $st->execute([':id' => $id, ':name' => trim($name)]);
    }

    public function deleteAuthor(int $id): void {
        $st = $this->db->prepare("DELETE FROM author WHERE id = :id");
        $st->execute([':id' => $id]);
    }
}