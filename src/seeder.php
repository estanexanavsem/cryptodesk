<?php

declare(strict_types=1);

/**
 * Populate categories and the 100 keyword articles. Idempotent: it wipes the
 * tables first, so it can be used both for first-run seeding and reseeding.
 */
function cd_seed(PDO $pdo): int
{
    // Optional cover-image manifest produced by bin/fetch_images.php.
    $manifestPath = dirname(__DIR__) . '/data/covers.json';
    $covers = is_file($manifestPath)
        ? (json_decode((string) file_get_contents($manifestPath), true) ?: [])
        : [];

    $pdo->beginTransaction();

    $pdo->exec('DELETE FROM articles');
    $pdo->exec('DELETE FROM categories');
    $pdo->exec('DELETE FROM sqlite_sequence WHERE name IN ("articles", "categories")');

    $catStmt = $pdo->prepare(
        'INSERT INTO categories (slug, name, tagline, description) VALUES (:slug, :name, :tagline, :description)'
    );
    $catId = [];
    foreach (cd_categories() as $slug => $cat) {
        $catStmt->execute([
            ':slug' => $slug,
            ':name' => $cat['name'],
            ':tagline' => $cat['tagline'],
            ':description' => $cat['description'],
        ]);
        $catId[$slug] = (int) $pdo->lastInsertId();
    }

    $artStmt = $pdo->prepare(
        'INSERT INTO articles
            (category_id, slug, title, keyword, excerpt, body, author, read_minutes, views, published_at,
             image, image_credit, image_credit_url, image_license, image_license_url, image_source)
         VALUES
            (:category_id, :slug, :title, :keyword, :excerpt, :body, :author, :read_minutes, :views, :published_at,
             :image, :image_credit, :image_credit_url, :image_license, :image_license_url, :image_source)'
    );

    $count = 0;
    foreach (cd_keywords() as $index => $kw) {
        $a = cd_generate_article($kw, $index);
        $cover = $covers[$a['slug']] ?? null;
        $artStmt->execute([
            ':category_id' => $catId[$a['category_slug']],
            ':slug' => $a['slug'],
            ':title' => $a['title'],
            ':keyword' => $a['keyword'],
            ':excerpt' => $a['excerpt'],
            ':body' => $a['body'],
            ':author' => $a['author'],
            ':read_minutes' => $a['read_minutes'],
            ':views' => $a['views'],
            ':published_at' => $a['published_at'],
            ':image' => $cover['file'] ?? null,
            ':image_credit' => $cover['creator'] ?? null,
            ':image_credit_url' => $cover['landing_url'] ?? null,
            ':image_license' => isset($cover['license']) ? trim(($cover['license'] ?? '') . ' ' . ($cover['license_version'] ?? '')) : null,
            ':image_license_url' => $cover['license_url'] ?? null,
            ':image_source' => $cover['source'] ?? null,
        ]);
        $count++;
    }

    $pdo->commit();

    return $count;
}
