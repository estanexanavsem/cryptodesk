<?php
/** @var string $content @var string $pageTitle @var array $nav @var string $siteName @var string $siteTagline @var array $meta */
$meta = $meta ?? [];
$base = site_base_url();
$desc = (string) ($meta['description'] ?? $siteTagline);
$canonical = $base . (string) ($meta['canonical'] ?? (strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/'));
$currentPath = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
$ogType = (string) ($meta['type'] ?? 'website');
$ogImage = !empty($meta['image']) ? $base . (string) $meta['image'] : '';

$ld = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'WebSite',
            '@id' => $base . '/#website',
            'url' => $base . '/',
            'name' => $siteName,
            'description' => $siteTagline,
            'publisher' => ['@id' => $base . '/#org'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => ['@type' => 'EntryPoint', 'urlTemplate' => $base . '/search?q={search_term_string}'],
                'query-input' => 'required name=search_term_string',
            ],
        ],
        ['@type' => 'Organization', '@id' => $base . '/#org', 'name' => $siteName, 'url' => $base . '/'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($desc) ?>">
    <?php if (!empty($meta['noindex'])): ?>
    <meta name="robots" content="noindex, follow">
    <?php endif; ?>
    <link rel="canonical" href="<?= e($canonical) ?>">

    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($desc) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:locale" content="en_US">
    <?php if ($ogImage !== ''): ?>
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <?php endif; ?>
    <?php if ($ogType === 'article'): ?>
    <meta property="article:published_time" content="<?= e((string) ($meta['published'] ?? '')) ?>">
    <meta property="article:author" content="<?= e((string) ($meta['author'] ?? '')) ?>">
    <meta property="article:section" content="<?= e((string) ($meta['section'] ?? '')) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="<?= $ogImage !== '' ? 'summary_large_image' : 'summary' ?>">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($desc) ?>">
    <?php if ($ogImage !== ''): ?>
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;0,6..72,700;1,6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' fill='%2316181d'/><rect x='7' y='9' width='18' height='2.6' fill='%23ffffff'/><rect x='7' y='14.7' width='18' height='2.6' fill='%23b3122a'/><rect x='7' y='20.4' width='12' height='2.6' fill='%23ffffff'/></svg>">
    <script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header">
    <div class="wrap masthead">
        <a class="brand" href="/" aria-label="<?= e($siteName) ?> — home">
            <span class="brand-rule" aria-hidden="true"></span>
            <span class="brand-name"><?= e($siteName) ?></span>
        </a>
        <p class="masthead-edition">Crypto&nbsp;Trading&nbsp;Tools · Est.&nbsp;2026</p>
        <form class="search" action="/search" method="get" role="search">
            <label class="visually-hidden" for="site-search">Search articles</label>
            <input type="search" id="site-search" name="q" placeholder="Search tools…" value="<?= e($_GET['q'] ?? '') ?>">
        </form>
    </div>
    <nav class="sectionbar" aria-label="Sections">
        <div class="wrap sectionbar-inner">
            <?php foreach ($nav as $c): ?>
                <a href="/category/<?= e($c['slug']) ?>" style="--chip:<?= category_color($c['slug']) ?>"<?= $currentPath === '/category/' . $c['slug'] ? ' aria-current="page"' : '' ?>><?= e($c['name']) ?></a>
            <?php endforeach; ?>
            <a class="sec-about" href="/about"<?= $currentPath === '/about' ? ' aria-current="page"' : '' ?>>About</a>
        </div>
    </nav>
</header>

<main class="wrap" id="main" tabindex="-1"><?= $content ?></main>

<footer class="site-footer">
    <div class="wrap footer-inner">
        <div>
            <strong><?= e($siteName) ?></strong>
            <p><?= e($siteTagline) ?>.</p>
        </div>
        <nav class="footer-nav" aria-label="Footer">
            <?php foreach ($nav as $c): ?>
                <a href="/category/<?= e($c['slug']) ?>"><?= e($c['name']) ?></a>
            <?php endforeach; ?>
        </nav>
        <p class="fineprint">Editorial demo. Nothing here is financial advice. © <?= date('Y') ?> <?= e($siteName) ?>.</p>
    </div>
</footer>
</body>
</html>
