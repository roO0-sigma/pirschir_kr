<article class="blog-full">
    <header>
        <h1><?= htmlspecialchars($blog['title']) ?></h1>
        <p class="blog-meta">
            Тема: <span><?= htmlspecialchars($blog['category_name']) ?></span> ·
            Автор: <a href="/?controller=blog&action=author&user_id=<?= (int)$blog['user_id'] ?>" class="author-link"><?= htmlspecialchars($blog['author_name']) ?></a> ·
            <span><?= htmlspecialchars($blog['created_at']) ?></span>
        </p>
    </header>

    <div class="blog-content">
        <?= nl2br(htmlspecialchars($blog['content'])) ?>
    </div>

    <div class="blog-actions">
        <a href="/?controller=blog&action=index" class="link">← К списку</a>
        <?php
        use App\Core\Auth;
        if (Auth::check() && (Auth::isModerator() || Auth::user()['id'] === $blog['user_id'])): ?>
            <a href="/?controller=blog&action=edit&id=<?= (int)$blog['id'] ?>" class="btn btn-small">Редактировать</a>
            <a href="/?controller=blog&action=delete&id=<?= (int)$blog['id'] ?>" class="btn btn-small btn-danger">Удалить</a>
        <?php endif; ?>
    </div>
</article>


