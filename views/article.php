<?php
/** @var array $article @var array $related @var array $popular @var array $nav */
$base = site_base_url();
$articleLd = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article['title'],
    'description' => $article['excerpt'],
    'datePublished' => iso_date((string) $article['published_at']),
    'dateModified' => iso_date((string) $article['published_at']),
    'author' => ['@type' => 'Person', 'name' => $article['author']],
    'publisher' => ['@type' => 'Organization', 'name' => $siteName],
    'articleSection' => $article['category_name'],
    'mainEntityOfPage' => $base . '/article/' . $article['slug'],
    'wordCount' => str_word_count(strip_tags((string) $article['body'])),
];
if (!empty($article['image'])) {
    $articleLd['image'] = $base . $article['image'];
}
$breadcrumbLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $article['category_name'], 'item' => $base . '/category/' . $article['category_slug']],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $article['keyword']],
    ],
];
?>
<script type="application/ld+json"><?= json_encode([$articleLd, $breadcrumbLd], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>

<nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/">Home</a> <span aria-hidden="true">/</span>
    <a href="/category/<?= e($article['category_slug']) ?>"><?= e($article['category_name']) ?></a>
    <span aria-hidden="true">/</span> <?= e($article['keyword']) ?>
</nav>

<article class="post">
    <header class="post-head">
        <a class="chip" href="/category/<?= e($article['category_slug']) ?>" style="--chip:<?= category_color($article['category_slug']) ?>"><?= e($article['category_name']) ?></a>
        <h1><?= e($article['title']) ?></h1>
        <p class="post-meta">
            By <strong><?= e($article['author']) ?></strong>
            · <time datetime="<?= e(iso_date($article['published_at'])) ?>"><?= e(format_date($article['published_at'])) ?></time>
            · <?= (int) $article['read_minutes'] ?> min read
            · <?= e(format_views((int) $article['views'])) ?> views
        </p>
    </header>

    <figure class="post-cover-wrap">
        <div class="post-cover"><?= cover_media($article, 960, 420) ?></div>
        <?= image_credit_html($article) ?>
    </figure>

    <div class="layout-2col">
        <div class="post-body">
            <?= $article['body'] /* trusted: generated server-side */ ?>

            <div class="post-tags">
                <span>Filed under</span>
                <a class="chip" href="/category/<?= e($article['category_slug']) ?>" style="--chip:<?= category_color($article['category_slug']) ?>"><?= e($article['category_name']) ?></a>
            </div>
        </div>
        <?= render_sidebar($popular, $nav) ?>
    </div>
</article>

<?php if ($related): ?>
<section class="related">
    <h2 class="section-title">More in <?= e($article['category_name']) ?></h2>
    <div class="grid">
        <?php foreach ($related as $a) { echo article_card($a); } ?>
    </div>
</section>
<?php endif; ?>
