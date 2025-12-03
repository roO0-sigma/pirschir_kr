<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (Auth::attempt($email, $password)) {
                $this->redirect('/?controller=blog&action=index');
            } else {
                $error = 'Неверный email или пароль';
            }
        }

        $this->render('auth/login', ['error' => $error]);
    }

    public function register(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if ($password !== $passwordConfirm) {
                $error = 'Пароли не совпадают';
            } elseif (!$name || !$email || !$password) {
                $error = 'Заполните все поля';
            } else {
                $userModel = new User();
                if ($userModel->findByEmail($email)) {
                    $error = 'Пользователь с таким email уже существует';
                } else {
                    $userModel->create($name, $email, $password, false);
                    Auth::attempt($email, $password);
                    $this->redirect('/?controller=blog&action=index');
                    return;
                }
            }
        }

        $this->render('auth/register', ['error' => $error]);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/?controller=blog&action=index');
    }
}


