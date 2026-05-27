<?php

declare(strict_types=1);

/**
 * Shared bootstrap: defines paths, wires up the database and view layer, and
 * seeds the blog on first run so a fresh checkout works with zero setup.
 *
 * Returns an array: ['pdo' => PDO, 'repo' => ArticleRepository, 'view' => View].
 */

use CryptoDesk\ArticleRepository;
use CryptoDesk\Database;
use CryptoDesk\View;

define('CD_ROOT', dirname(__DIR__));
define('CD_DATA', CD_ROOT . '/data');
define('CD_VIEWS', CD_ROOT . '/views');
define('CD_DB', getenv('CD_DB') ?: CD_DATA . '/cryptodesk.sqlite');
define('CD_SITE_NAME', 'MarketDesk');
define('CD_SITE_TAGLINE', 'Tools, tactics and infrastructure for active traders');

require CD_ROOT . '/src/Database.php';
require CD_ROOT . '/src/ArticleRepository.php';
require CD_ROOT . '/src/View.php';
require CD_ROOT . '/src/helpers.php';
require CD_ROOT . '/src/content.php';
require CD_ROOT . '/src/seeder.php';

$pdo = Database::connect(CD_DB);
Database::migrate($pdo);

// Zero-config: seed the 100 articles the first time the app boots.
if (Database::articleCount($pdo) === 0) {
    cd_seed($pdo);
}

$repo = new ArticleRepository($pdo);

$view = new View(CD_VIEWS, [
    'siteName' => CD_SITE_NAME,
    'siteTagline' => CD_SITE_TAGLINE,
    'nav' => $repo->categories(),
    'pageTitle' => CD_SITE_NAME,
]);

return ['pdo' => $pdo, 'repo' => $repo, 'view' => $view];
