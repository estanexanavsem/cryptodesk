<?php

declare(strict_types=1);

/**
 * Front controller. With PHP's built-in server it doubles as the router:
 * existing static files are served directly, everything else routes here.
 */

if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false; // let the built-in server serve the asset
    }
}

/** @var array{pdo: PDO, repo: \CryptoDesk\ArticleRepository, view: \CryptoDesk\View} $app */
$app = require dirname(__DIR__) . '/src/bootstrap.php';
$repo = $app['repo'];
$view = $app['view'];

$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
if ($path === '') {
    $path = '/';
}
$segments = $path === '/' ? [] : explode('/', ltrim($path, '/'));

$perPage = 9;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// --- Routing -------------------------------------------------------------

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    $base = site_base_url();
    echo "User-agent: *\nAllow: /\nDisallow: /search\n\nSitemap: {$base}/sitemap.xml\n";
    return;
}

if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo cd_sitemap_xml($repo, site_base_url());
    return;
}

if ($path === '/') {
    $featured = $repo->latest(1)[0] ?? null;
    $articles = $featured ? array_slice($repo->latest(13), 1) : $repo->latest(12);
    $view->page('home', [
        'featured' => $featured,
        'articles' => $articles,
        'popular' => $repo->popular(5),
        'totalArticles' => $repo->countAll(),
    ], CD_SITE_NAME . ' — ' . CD_SITE_TAGLINE, [
        'description' => 'Hands-on guides to mining hardware — rigs, cooling, power, hosting, network validation and ROI. ' . $repo->countAll() . ' deep dives.',
        'canonical' => '/',
        'image' => $featured['image'] ?? null,
    ]);
    return;
}

if ($path === '/search') {
    $query = trim((string) ($_GET['q'] ?? ''));
    $results = $query !== '' ? $repo->search($query, 30) : [];
    $view->page('search', [
        'query' => $query,
        'results' => $results,
        'popular' => $repo->popular(5),
    ], 'Search' . ($query !== '' ? ': ' . $query : '') . ' — ' . CD_SITE_NAME, [
        'description' => 'Search ' . CD_SITE_NAME . ' for rigs, parts and topics.',
        'canonical' => '/search',
        'noindex' => true,
    ]);
    return;
}

if ($path === '/about') {
    $view->page('about', [
        'totalArticles' => $repo->countAll(),
        'categories' => $repo->categories(),
    ], 'About ' . CD_SITE_NAME, [
        'description' => 'About ' . CD_SITE_NAME . ' — an editorial demo: ' . $repo->countAll() . ' guides on the gear and decisions mining operators reach for.',
        'canonical' => '/about',
    ]);
    return;
}

if ($segments[0] === 'category' && isset($segments[1])) {
    $category = $repo->findCategory($segments[1]);
    if ($category === null) {
        not_found($view, $repo);
        return;
    }
    $total = $repo->countByCategory($category['slug']);
    $articles = $repo->byCategory($category['slug'], $perPage, $offset);
    $canonical = '/category/' . $category['slug'] . ($page > 1 ? '?page=' . $page : '');
    $view->page('category', [
        'category' => $category,
        'articles' => $articles,
        'popular' => $repo->popular(5),
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total,
    ], $category['name'] . ($page > 1 ? ' (page ' . $page . ')' : '') . ' — ' . CD_SITE_NAME, [
        'description' => $category['description'],
        'canonical' => $canonical,
        'image' => $articles[0]['image'] ?? null,
    ]);
    return;
}

if ($segments[0] === 'article' && isset($segments[1])) {
    $article = $repo->findBySlug($segments[1]);
    if ($article === null) {
        not_found($view, $repo);
        return;
    }
    $repo->incrementViews((int) $article['id']);
    $view->page('article', [
        'article' => $article,
        'related' => $repo->related($article, 4),
        'popular' => $repo->popular(5),
    ], $article['title'] . ' — ' . CD_SITE_NAME, [
        'description' => $article['excerpt'],
        'canonical' => '/article/' . $article['slug'],
        'image' => $article['image'] ?? null,
        'type' => 'article',
        'published' => iso_date((string) $article['published_at']),
        'author' => $article['author'],
        'section' => $article['category_name'],
    ]);
    return;
}

not_found($view, $repo);

function not_found(\CryptoDesk\View $view, \CryptoDesk\ArticleRepository $repo): void
{
    http_response_code(404);
    $view->page('404', ['popular' => $repo->popular(5)], 'Not found — ' . CD_SITE_NAME, [
        'description' => 'The page you were after does not exist or has moved.',
        'noindex' => true,
    ]);
}

/** Build an XML sitemap covering the home page, about, categories and all articles. */
function cd_sitemap_xml(\CryptoDesk\ArticleRepository $repo, string $base): string
{
    $urls = [
        ['loc' => $base . '/', 'changefreq' => 'daily', 'priority' => '1.0'],
        ['loc' => $base . '/about', 'changefreq' => 'monthly', 'priority' => '0.3'],
    ];
    foreach ($repo->categories() as $c) {
        $urls[] = ['loc' => $base . '/category/' . $c['slug'], 'changefreq' => 'weekly', 'priority' => '0.6'];
    }
    foreach ($repo->latest(1000) as $a) {
        $urls[] = [
            'loc' => $base . '/article/' . $a['slug'],
            'lastmod' => date('Y-m-d', strtotime((string) $a['published_at']) ?: time()),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        $xml .= '  <url><loc>' . e($u['loc']) . '</loc>';
        if (!empty($u['lastmod'])) {
            $xml .= '<lastmod>' . $u['lastmod'] . '</lastmod>';
        }
        $xml .= '<changefreq>' . $u['changefreq'] . '</changefreq>'
            . '<priority>' . $u['priority'] . '</priority></url>' . "\n";
    }

    return $xml . '</urlset>' . "\n";
}
