<?php /** @var array $category @var array $articles @var array $popular @var array $nav @var int $page @var int $perPage @var int $total */ ?>

<nav class="breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a> <span aria-hidden="true">/</span> <?= e($category['name']) ?></nav>

<header class="page-head" style="--chip:<?= category_color($category['slug']) ?>">
    <p class="kicker"><?= e($category['tagline']) ?></p>
    <h1><?= e($category['name']) ?></h1>
    <p class="lede"><?= e($category['description']) ?></p>
</header>

<div class="layout-2col">
    <div>
        <h2 class="visually-hidden">Articles in <?= e($category['name']) ?></h2>
        <?php if ($articles): ?>
            <div class="grid">
                <?php foreach ($articles as $a) { echo article_card($a); } ?>
            </div>
            <?php
            $pages = (int) ceil($total / $perPage);
            if ($pages > 1): ?>
                <nav class="pagination" aria-label="Pagination">
                    <?php if ($page > 1): ?>
                        <a href="/category/<?= e($category['slug']) ?>?page=<?= $page - 1 ?>">← Newer</a>
                    <?php endif; ?>
                    <span class="page-of">Page <?= $page ?> of <?= $pages ?></span>
                    <?php if ($page < $pages): ?>
                        <a href="/category/<?= e($category['slug']) ?>?page=<?= $page + 1 ?>">Older →</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <p class="empty">No articles here yet.</p>
        <?php endif; ?>
    </div>
    <?= render_sidebar($popular, $nav) ?>
</div>
