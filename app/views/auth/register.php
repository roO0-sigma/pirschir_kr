<section class="auth-card">
    <h1>Регистрация</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Имя
            <input type="text" name="name" required>
        </label>
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Пароль
            <input type="password" name="password" required>
        </label>
        <label>
            Подтверждение пароля
            <input type="password" name="password_confirm" required>
        </label>
        <button type="submit" class="btn">Создать аккаунт</button>
    </form>

    <p class="muted">
        Уже есть аккаунт?
        <a href="/?controller=auth&action=login">Войти</a>
    </p>
</section>


