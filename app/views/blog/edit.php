<section class="form-card">
    <h1>Редактирование блога</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Заголовок
            <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>
        </label>
        <label>
            Тема
            <select name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>" <?= $blog['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Содержимое
            <textarea name="content" rows="8" required><?= htmlspecialchars($blog['content']) ?></textarea>
        </label>
        <button type="submit" class="btn">Сохранить</button>
    </form>
</section>


