<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class Subscription
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function subscribe(int $subscriberId, int $authorId): bool
    {
        // Нельзя подписаться на самого себя
        if ($subscriberId === $authorId) {
            return false;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO subscriptions (subscriber_id, author_id) 
             VALUES (:subscriber_id, :author_id)'
        );

        try {
            return $stmt->execute([
                ':subscriber_id' => $subscriberId,
                ':author_id' => $authorId,
            ]);
        } catch (\PDOException $e) {
            // Если подписка уже существует, вернуть false
            return false;
        }
    }

    public function unsubscribe(int $subscriberId, int $authorId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM subscriptions 
             WHERE subscriber_id = :subscriber_id AND author_id = :author_id'
        );

        return $stmt->execute([
            ':subscriber_id' => $subscriberId,
            ':author_id' => $authorId,
        ]);
    }

    public function isSubscribed(int $subscriberId, int $authorId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as cnt 
             FROM subscriptions 
             WHERE subscriber_id = :subscriber_id AND author_id = :author_id'
        );
        $stmt->execute([
            ':subscriber_id' => $subscriberId,
            ':author_id' => $authorId,
        ]);
        $row = $stmt->fetch();
        return $row && (int)$row['cnt'] > 0;
    }

    public function getSubscriptions(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT u.* 
             FROM subscriptions s
             JOIN users u ON s.author_id = u.id
             WHERE s.subscriber_id = :user_id
             ORDER BY s.created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}

