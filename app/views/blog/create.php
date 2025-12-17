<section class="form-card">
    <h1>Новый пост</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Заголовок
            <input type="text" name="title" required>
        </label>
        <label>
            Тема
            <select name="category_id" required>
                <option value="">Выберите тему</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Содержимое
            <textarea name="content" rows="8" required></textarea>
        </label>
        <button type="submit" class="btn">Создать</button>
    </form>
</section>


