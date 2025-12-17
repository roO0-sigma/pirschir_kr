<section class="page-header">
    <h1>Мои подписки</h1>
    <p class="muted">Блоги авторов, на которых вы подписаны</p>
</section>

<?php if (empty($subscribedAuthors)): ?>
    <p class="muted">Вы пока ни на кого не подписаны. <a href="/?controller=blog&action=index">Найти авторов</a></p>
<?php else: ?>
    <?php if (empty($blogs)): ?>
        <p class="muted">У авторов, на которых вы подписаны, пока нет публикаций.</p>
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
                        Автор: <a href="/?controller=blog&action=author&user_id=<?= (int)$blog['author_id'] ?>" class="author-link"><?= htmlspecialchars($blog['author_name']) ?></a> ·
                        <span><?= htmlspecialchars($blog['created_at']) ?></span>
                    </p>
                    <p class="blog-excerpt">
                        <?= nl2br(htmlspecialchars(mb_strimwidth($blog['content'], 0, 200, '...'))) ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="blog-actions">
    <a href="/?controller=blog&action=index" class="link">← К списку блогов</a>
</div>

