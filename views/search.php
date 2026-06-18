<?php /** @var string $query @var array $results @var array $popular @var array $nav */ ?>

<header class="page-head">
    <p class="kicker">Search</p>
    <h1><?= $query !== '' ? 'Results for “' . e($query) . '”' : 'Search the desk' ?></h1>
    <?php if ($query !== ''): ?>
        <p class="lede"><?= count($results) ?> article<?= count($results) === 1 ? '' : 's' ?> found.</p>
    <?php else: ?>
        <p class="lede">Type a part, a rig or a topic into the box above.</p>
    <?php endif; ?>
</header>

<div class="layout-2col">
    <div>
        <h2 class="visually-hidden">Search results</h2>
        <?php if ($query !== '' && $results === []): ?>
            <p class="empty">Nothing matched “<?= e($query) ?>”. Try a broader term.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($results as $a) { echo article_card($a); } ?>
            </div>
        <?php endif; ?>
    </div>
    <?= render_sidebar($popular, $nav) ?>
</div>
