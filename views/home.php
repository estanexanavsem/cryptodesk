<?php /** @var array|null $featured @var array $articles @var array $popular @var array $nav @var int $totalArticles @var string $siteTagline */ ?>

<section class="hero">
    <div class="hero-copy">
        <p class="kicker">CryptoDesk</p>
        <h1><?= e($siteTagline) ?></h1>
        <p class="lede">Hands-on guides to terminals, indicators, DeFi plumbing and the APIs that hold a trading operation together — <?= (int) $totalArticles ?> deep dives and counting.</p>
    </div>
</section>

<?php if ($featured): ?>
<a class="featured" href="/article/<?= e($featured['slug']) ?>">
    <div class="featured-cover"><?= cover_media($featured, 720, 480) ?></div>
    <div class="featured-body">
        <span class="chip" style="--chip:<?= category_color($featured['category_slug']) ?>"><?= e($featured['category_name']) ?></span>
        <h2><?= e($featured['title']) ?></h2>
        <p><?= e($featured['excerpt']) ?></p>
        <p class="meta"><?= e($featured['author']) ?> · <?= e(time_ago($featured['published_at'])) ?> · <?= (int) $featured['read_minutes'] ?> min read</p>
    </div>
</a>
<?php endif; ?>

<div class="layout-2col">
    <div>
        <h2 class="section-title">Latest</h2>
        <div class="grid">
            <?php foreach ($articles as $a) { echo article_card($a); } ?>
        </div>
    </div>
    <?= render_sidebar($popular, $nav) ?>
</div>
