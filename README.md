# MarketDesk

A small news/blog about trading tools — terminals, indicators, DeFi plumbing, on-chain analytics, APIs and custody. **100 articles** are generated from a keyword list and grouped into 7 topics. Each article has a real, openly-licensed cover photo (downloaded from Openverse), falling back to a generated SVG chart when no photo is available.

Built to stay simple: **plain PHP 8.3**, a single **SQLite** file, **zero runtime dependencies**, no build step.

## Stack

| Concern        | Choice                                   |
|----------------|------------------------------------------|
| Language       | PHP 8.3 (no framework)                   |
| Storage        | SQLite via PDO (`data/cryptodesk.sqlite`)|
| Routing        | Single front controller (`public/index.php`) |
| Templates      | Plain PHP views + one tiny `View` class  |
| Media          | Procedurally generated inline SVG charts |
| Styling        | Hand-written CSS, dark "fintech" theme   |

## Run it

### Option A — PHP built-in server (simplest)

```bash
php -S localhost:8000 -t public public/index.php
```

Open <http://localhost:8000>. The database is created and seeded with all 100 articles automatically on first request.

### Cover photos

Real, openly-licensed cover photos ship with the project (in `public/assets/covers/`
with an attribution manifest at `data/covers.json`), so the blog shows images on
first run — no network needed. Photos come from [Openverse](https://openverse.org)
(Creative Commons / public domain); credit + licence are shown under each cover.

To refresh them (e.g. for different shots), re-run the downloader and reseed:

```bash
php bin/fetch_images.php          # fill any missing covers
php bin/fetch_images.php --force  # re-download everything
php bin/seed.php                  # reattach covers to articles
```

Any article without a photo falls back to a generated SVG chart automatically.

### Option B — Docker (no local PHP needed)

```bash
docker compose up
# or, without compose:
docker run --rm -p 8000:8000 -v "$PWD":/app -w /app php:8.3-cli \
    php -S 0.0.0.0:8000 -t public public/index.php
```

Then open <http://localhost:8000>.

## Reseeding

The blog auto-seeds when the database is empty. To rebuild content explicitly:

```bash
php bin/seed.php           # wipe tables and reseed
php bin/seed.php --fresh    # delete the SQLite file first, then reseed
```

Content generation is deterministic (seeded by keyword index), so a reseed always reproduces the same articles, dates and charts.

## Layout

```
cryptodesk/
├── public/
│   ├── index.php          # front controller + router
│   ├── .htaccess          # Apache rewrite + protects the DB
│   └── assets/style.css
├── src/
│   ├── bootstrap.php      # paths, DB wiring, first-run seeding
│   ├── Database.php       # PDO/SQLite connection + schema
│   ├── ArticleRepository.php
│   ├── View.php           # template renderer
│   ├── helpers.php        # escaping, formatting, SVG chart generator, cards
│   ├── content.php        # 100 keywords → categories + article generator
│   └── seeder.php         # populates the database
├── views/                 # layout, home, category, article, search, about, 404
├── bin/seed.php           # CLI (re)seeder
└── data/                  # SQLite file lives here (git-ignored)
```

## Routes

| Path                  | Page                              |
|-----------------------|-----------------------------------|
| `/`                   | Home — featured + latest grid     |
| `/article/{slug}`     | Single article (+ related)        |
| `/category/{slug}`    | Topic listing (paginated)         |
| `/search?q=…`         | Search across titles/keywords     |
| `/about`              | About + topic overview            |

## SEO & accessibility

Audited at **100/100** for Accessibility, SEO and Best Practices (Lighthouse, desktop + mobile).

- Per-page `<title>` + meta description, canonical URLs, Open Graph + Twitter Card tags.
- JSON-LD structured data: `WebSite` + `Organization` site-wide, `NewsArticle` +
  `BreadcrumbList` on articles.
- `/sitemap.xml` (all pages, generated) and `/robots.txt` (served by the router).
- One `<h1>` per page with a clean heading hierarchy; semantic landmarks
  (`header`/`nav`/`main`/`aside`/`footer`), labelled nav/search, `aria-current`
  on the active section, breadcrumbs.
- Skip-to-content link, visible `:focus-visible` rings, AA colour contrast,
  `<time datetime>` on dates, decorative cover images kept out of the tab order,
  `prefers-reduced-motion` honoured.

## Notes

All content is illustrative and generated for demonstration — it is **not** financial advice.
