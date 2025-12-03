<section class="form-card">
    <h1>Удаление блога</h1>
    <p>Вы действительно хотите удалить блог «<?= htmlspecialchars($blog['title']) ?>»?</p>

    <form method="post">
        <button type="submit" class="btn btn-danger">Удалить</button>
        <a href="/?controller=blog&action=show&id=<?= (int)$blog['id'] ?>" class="btn btn-outline">Отмена</a>
    </form>
</section>


