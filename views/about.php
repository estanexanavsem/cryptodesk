<?php /** @var int $totalArticles @var array $categories @var string $siteName @var array $nav */ ?>

<header class="page-head">
    <p class="kicker">About</p>
    <h1>What <?= e($siteName) ?> is</h1>
    <p class="lede"><?= e($siteName) ?> is an editorial demo: <?= (int) $totalArticles ?> guides covering the gear and decisions mining operators reach for, from rigs and cooling to hosting and payback.</p>
</header>

<div class="prose-narrow">
    <p>This site is built on a deliberately small stack — plain PHP 8.3, a single SQLite file and zero runtime dependencies. Charts are generated as inline SVG, so there are no image files or third-party calls to wait on.</p>
    <p>Every article is seeded from a keyword and grouped into one of <?= count($categories) ?> topics:</p>
    <div class="topic-grid">
        <?php foreach ($categories as $c): ?>
            <a class="topic-card" href="/category/<?= e($c['slug']) ?>" style="--chip:<?= category_color($c['slug']) ?>">
                <strong><?= e($c['name']) ?></strong>
                <span><?= e($c['tagline']) ?></span>
                <em><?= (int) $c['article_count'] ?> articles</em>
            </a>
        <?php endforeach; ?>
    </div>
    <p class="disclaimer">Content is illustrative and generated for demonstration. It is not financial advice.</p>
</div>
