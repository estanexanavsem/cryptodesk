<?php

declare(strict_types=1);

namespace CryptoDesk;

use PDO;

/** Thin wrapper around a single shared PDO/SQLite connection. */
final class Database
{
    private static ?PDO $pdo = null;

    public static function connect(string $path): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA journal_mode = WAL;');
        $pdo->exec('PRAGMA foreign_keys = ON;');
        // Wait (rather than fail) when another process holds a write lock —
        // important under Apache's concurrent workers on one SQLite file.
        $pdo->exec('PRAGMA busy_timeout = 5000;');

        self::$pdo = $pdo;
        return $pdo;
    }

    public static function migrate(PDO $pdo): void
    {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS categories (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                slug        TEXT NOT NULL UNIQUE,
                name        TEXT NOT NULL,
                tagline     TEXT NOT NULL DEFAULT "",
                description TEXT NOT NULL DEFAULT ""
            )'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS articles (
                id            INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id   INTEGER NOT NULL REFERENCES categories(id),
                slug          TEXT NOT NULL UNIQUE,
                title         TEXT NOT NULL,
                keyword       TEXT NOT NULL,
                excerpt       TEXT NOT NULL,
                body          TEXT NOT NULL,
                author        TEXT NOT NULL,
                read_minutes  INTEGER NOT NULL DEFAULT 4,
                views         INTEGER NOT NULL DEFAULT 0,
                published_at  TEXT NOT NULL,
                image         TEXT,
                image_credit  TEXT,
                image_credit_url TEXT,
                image_license TEXT,
                image_license_url TEXT,
                image_source  TEXT
            )'
        );

        // Add cover-image columns to databases created before they existed.
        $existing = array_column($pdo->query('PRAGMA table_info(articles)')->fetchAll(), 'name');
        $imageColumns = ['image', 'image_credit', 'image_credit_url', 'image_license', 'image_license_url', 'image_source'];
        foreach ($imageColumns as $col) {
            if (!in_array($col, $existing, true)) {
                $pdo->exec("ALTER TABLE articles ADD COLUMN {$col} TEXT");
            }
        }

        $pdo->exec('CREATE INDEX IF NOT EXISTS idx_articles_category ON articles(category_id)');
        $pdo->exec('CREATE INDEX IF NOT EXISTS idx_articles_published ON articles(published_at DESC)');
    }

    /** Number of seeded articles, or 0 if the table does not yet exist. */
    public static function articleCount(PDO $pdo): int
    {
        try {
            return (int) $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
        } catch (\PDOException) {
            return 0;
        }
    }
}
