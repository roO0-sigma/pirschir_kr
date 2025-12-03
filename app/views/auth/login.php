<section class="auth-card">
    <h1>Вход</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Email
            <input type="email" name="email" required>
        </label>
        <label>
            Пароль
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn">Войти</button>
    </form>

    <p class="muted">
        Нет аккаунта?
        <a href="/?controller=auth&action=register">Зарегистрироваться</a>
    </p>
</section>


