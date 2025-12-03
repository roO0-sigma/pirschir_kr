<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function isModerator(): bool
    {
        return self::check() && !empty($_SESSION['user']['is_moderator']);
    }

    public static function attempt(string $email, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'is_moderator' => (bool)$user['is_moderator'],
            ];
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }
}


