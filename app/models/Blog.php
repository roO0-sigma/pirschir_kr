<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Blog
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(?string $search = null, ?int $categoryId = null): array
    {
        $sql = 'SELECT b.*, c.name AS category_name, u.name AS author_name 
                FROM blogs b
                JOIN categories c ON b.category_id = c.id
                JOIN users u ON b.user_id = u.id
                WHERE 1=1';
        $params = [];

        if ($search) {
            $sql .= ' AND (b.title LIKE :search OR b.content LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        if ($categoryId) {
            $sql .= ' AND b.category_id = :category_id';
            $params[':category_id'] = $categoryId;
        }

        $sql .= ' ORDER BY b.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, c.name AS category_name, u.name AS author_name 
             FROM blogs b
             JOIN categories c ON b.category_id = c.id
             JOIN users u ON b.user_id = u.id
             WHERE b.id = :id'
        );
        $stmt->execute([':id' => $id]);
        $blog = $stmt->fetch();
        return $blog ?: null;
    }

    public function create(int $userId, int $categoryId, string $title, string $content): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO blogs (user_id, category_id, title, content) 
             VALUES (:user_id, :category_id, :title, :content)'
        );

        return $stmt->execute([
            ':user_id' => $userId,
            ':category_id' => $categoryId,
            ':title' => $title,
            ':content' => $content,
        ]);
    }

    public function update(int $id, int $categoryId, string $title, string $content): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE blogs SET category_id = :category_id, title = :title, content = :content 
             WHERE id = :id'
        );

        return $stmt->execute([
            ':id' => $id,
            ':category_id' => $categoryId,
            ':title' => $title,
            ':content' => $content,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM blogs WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function belongsToUser(int $blogId, int $userId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as cnt FROM blogs WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $blogId, ':user_id' => $userId]);
        $row = $stmt->fetch();
        return $row && (int)$row['cnt'] > 0;
    }
}


