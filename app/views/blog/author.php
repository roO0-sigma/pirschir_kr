<section class="page-header">
    <h1>Публикации автора: <?= htmlspecialchars($author['name']) ?></h1>
    <p class="muted">Все публикации пользователя</p>
    <?php
    use App\Core\Auth;
    if (Auth::check() && Auth::user()['id'] != $author['id']): ?>
        <?php if ($isSubscribed): ?>
            <a href="/?controller=blog&action=unsubscribe&user_id=<?= (int)$author['id'] ?>" class="btn btn-danger">Отписаться</a>
        <?php else: ?>
            <a href="/?controller=blog&action=subscribe&user_id=<?= (int)$author['id'] ?>" class="btn btn-cool">Подписаться</a>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php if (empty($blogs)): ?>
    <p class="muted">У этого автора пока нет публикаций.</p>
<?php else: ?>
    <div class="blog-list">
        <?php foreach ($blogs as $blog): ?>
            <article class="blog-card">
                <h2>
                    <a href="/?controller=blog&action=show&id=<?= (int)$blog['id'] ?>">
                        <?= htmlspecialchars($blog['title']) ?>
                    </a>
                </h2>
                <p class="blog-meta">
                    Тема: <span><?= htmlspecialchars($blog['category_name']) ?></span> ·
                    <span><?= htmlspecialchars($blog['created_at']) ?></span>
                </p>
                <p class="blog-excerpt">
                    <?= nl2br(htmlspecialchars(mb_strimwidth($blog['content'], 0, 200, '...'))) ?>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="blog-actions">
    <a href="/?controller=blog&action=index" class="link">← К списку блогов</a>
</div>

