<?php

declare(strict_types=1);

namespace CryptoDesk;

use PDO;

/** All reads/writes for articles and categories live here. */
final class ArticleRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    private const SELECT = 'SELECT a.*, c.slug AS category_slug, c.name AS category_name
        FROM articles a JOIN categories c ON c.id = a.category_id';

    /** @return list<array<string,mixed>> */
    public function latest(int $limit = 12, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare(self::SELECT . ' ORDER BY a.published_at DESC LIMIT :l OFFSET :o');
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(self::SELECT . ' WHERE a.slug = :slug');
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /** @return list<array<string,mixed>> */
    public function byCategory(string $categorySlug, int $limit = 12, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare(
            self::SELECT . ' WHERE c.slug = :cat ORDER BY a.published_at DESC LIMIT :l OFFSET :o'
        );
        $stmt->bindValue(':cat', $categorySlug);
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByCategory(string $categorySlug): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM articles a JOIN categories c ON c.id = a.category_id WHERE c.slug = :cat'
        );
        $stmt->execute([':cat' => $categorySlug]);

        return (int) $stmt->fetchColumn();
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
    }

    /** Most-viewed articles, for the sidebar. @return list<array<string,mixed>> */
    public function popular(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(self::SELECT . ' ORDER BY a.views DESC LIMIT :l');
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** Other articles in the same category. @return list<array<string,mixed>> */
    public function related(array $article, int $limit = 4): array
    {
        $stmt = $this->pdo->prepare(
            self::SELECT . ' WHERE a.category_id = :cid AND a.id != :id ORDER BY a.published_at DESC LIMIT :l'
        );
        $stmt->bindValue(':cid', (int) $article['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':id', (int) $article['id'], PDO::PARAM_INT);
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** Naive LIKE search over titles, keywords and excerpts. @return list<array<string,mixed>> */
    public function search(string $query, int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            self::SELECT . ' WHERE a.title LIKE :q OR a.keyword LIKE :q OR a.excerpt LIKE :q
            ORDER BY a.published_at DESC LIMIT :l'
        );
        $stmt->bindValue(':q', '%' . $query . '%');
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function incrementViews(int $id): void
    {
        // A view-count bump must never break the page if the DB is briefly locked.
        try {
            $stmt = $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (\PDOException) {
            // ignore — cosmetic counter only
        }
    }

    /** @return list<array<string,mixed>> */
    public function categories(): array
    {
        return $this->pdo->query(
            'SELECT c.*, COUNT(a.id) AS article_count
             FROM categories c LEFT JOIN articles a ON a.category_id = c.id
             GROUP BY c.id ORDER BY c.name'
        )->fetchAll();
    }

    public function findCategory(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE slug = :slug');
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch();

        return $row ?: null;
    }
}
