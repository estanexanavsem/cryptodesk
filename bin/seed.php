#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI seeder. Rebuilds the blog content from scratch.
 *
 *   php bin/seed.php          # (re)seed the database
 *   php bin/seed.php --fresh  # delete the SQLite file first, then seed
 */

$root = dirname(__DIR__);

if (in_array('--fresh', $argv, true)) {
    $db = getenv('CD_DB') ?: $root . '/data/cryptodesk.sqlite';
    foreach ([$db, $db . '-wal', $db . '-shm'] as $f) {
        if (is_file($f)) {
            unlink($f);
        }
    }
    fwrite(STDOUT, "Removed existing database.\n");
}

/** @var array{pdo: PDO} $app */
$app = require $root . '/src/bootstrap.php';

// bootstrap() auto-seeds an empty DB; force a clean reseed regardless.
$count = cd_seed($app['pdo']);

fwrite(STDOUT, sprintf("Seeded %d articles across %d categories.\n", $count, count(cd_categories())));
