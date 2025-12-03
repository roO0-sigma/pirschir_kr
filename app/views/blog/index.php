<section class="page-header">
    <h1>Блоги</h1>
    <p class="muted">Поиск и фильтрация по темам</p>
</section>

<form method="get" class="filters">
    <input type="hidden" name="controller" value="blog">
    <input type="hidden" name="action" value="index">

    <input type="text" name="q" placeholder="Поиск по заголовку и содержимому" value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit" class="btn">Найти</button>
</form>

<div class="category-cards">
    <a href="/?controller=blog&action=index" class="category-card <?= empty($categoryId) ? 'active' : '' ?>">
        <div class="category-card-image category-card-image-default"></div>
        <div class="category-card-footer">Все темы</div>
    </a>
    <?php foreach ($categories as $category): ?>
        <?php
        $imagePath = $category['image_path'] ?? null;
        $safePath = $imagePath ? htmlspecialchars($imagePath, ENT_QUOTES) : null;
        $bgUrl = $safePath ? ' style="background-image: url(\'' . $safePath . '\');"' : '';
        ?>
        <a href="/?controller=blog&action=index&category=<?= (int)$category['id'] ?>"
           class="category-card <?= isset($categoryId) && $categoryId == $category['id'] ? 'active' : '' ?>">
            <div class="category-card-image<?= $imagePath ? '' : ' category-card-image-default' ?>"<?= $bgUrl ?>></div>
            <div class="category-card-footer">
                <?= htmlspecialchars($category['name']) ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($blogs)): ?>
    <p class="muted">Блогов пока нет.</p>
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
                    Автор: <span><?= htmlspecialchars($blog['author_name']) ?></span> ·
                    <span><?= htmlspecialchars($blog['created_at']) ?></span>
                </p>
                <p class="blog-excerpt">
                    <?= nl2br(htmlspecialchars(mb_strimwidth($blog['content'], 0, 200, '...'))) ?>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


