<?php
use App\Core\Auth;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Агрегатор блогов</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a href="/?controller=blog&action=index" class="logo">Блоги</a>
        <nav class="nav">
            <?php if (Auth::check()): ?>
                <span class="nav-user">Привет, <?= htmlspecialchars(Auth::user()['name']) ?></span>
                <a href="/?controller=blog&action=create" class="btn">Новый блог</a>
                <a href="/?controller=auth&action=logout" class="link">Выйти</a>
            <?php else: ?>
                <a href="/?controller=auth&action=login" class="link">Войти</a>
                <a href="/?controller=auth&action=register" class="btn btn-outline">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
    <?php include $viewFile; ?>
</main>
<footer class="footer">
    <div class="container">
        <small>Веб-сервис по агрегации блогов</small>
    </div>
</footer>
</body>
</html>


