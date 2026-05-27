<?php

declare(strict_types=1);

/** Escape a value for safe output in HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Absolute base URL for the current request (scheme + host), for canonical/OG/sitemap. */
function site_base_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return ($https ? 'https' : 'http') . '://' . $host;
}

/** ISO-8601 timestamp for a stored datetime, for <time> / structured data. */
function iso_date(string $datetime): string
{
    $ts = strtotime($datetime);

    return $ts ? date('c', $ts) : '';
}

/** Human-friendly relative time, e.g. "3 days ago". */
function time_ago(string $datetime): string
{
    $ts = strtotime($datetime);
    if ($ts === false) {
        return $datetime;
    }

    $diff = time() - $ts;
    if ($diff < 60) {
        return 'just now';
    }

    $units = [
        31536000 => 'year',
        2592000 => 'month',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
    ];

    foreach ($units as $seconds => $label) {
        if ($diff >= $seconds) {
            $count = (int) floor($diff / $seconds);
            return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
        }
    }

    return 'just now';
}

/** Compact view count, e.g. 12.4k. */
function format_views(int $views): string
{
    if ($views >= 1000) {
        return number_format($views / 1000, 1) . 'k';
    }

    return (string) $views;
}

/** Pretty publish date, e.g. "27 May 2026". */
function format_date(string $datetime): string
{
    $ts = strtotime($datetime);

    return $ts ? date('j M Y', $ts) : $datetime;
}

/**
 * Deterministic accent colour for a category, so chips/cards are consistent.
 */
function category_color(string $slug): string
{
    // Muted, serious "section" tones that read well as tags on a light paper UI.
    $colors = [
        'trading-platforms' => '#21478f',  // navy
        'technical-analysis' => '#5b4b9e', // muted violet
        'defi-liquidity' => '#1d7a45',     // forest green
        'on-chain-analytics' => '#a4670f', // ochre
        'portfolio-risk' => '#46506a',     // graphite slate
        'automation-apis' => '#0f6e7d',    // teal
        'wallets-security' => '#8a2c6b',   // plum
    ];

    return $colors[$slug] ?? '#46506a';
}

/** Tiny deterministic PRNG (LCG) seeded by an integer; returns floats in [0,1). */
function cd_rng(int $seed): \Closure
{
    $state = ($seed ^ 0x9e3779b9) & 0x7fffffff;
    if ($state === 0) {
        $state = 1;
    }

    return static function () use (&$state): float {
        $state = (int) (($state * 1103515245 + 12345) & 0x7fffffff);
        return $state / 0x7fffffff;
    };
}

/**
 * Render a self-contained candlestick chart as inline SVG. Fully deterministic
 * for a given $seed, so every article gets its own stable "chart" artwork with
 * no external image files or network calls.
 */
function cd_chart_svg(string $seed, string $color, int $width = 720, int $height = 360): string
{
    $rand = cd_rng((int) crc32($seed));
    $pad = 18;
    $plotH = $height - $pad * 2;
    $plotW = $width - $pad * 2;

    $count = max(14, (int) floor($plotW / 16));
    $slot = $plotW / $count;
    $bodyW = $slot * 0.55;

    // Random-walk OHLC series.
    $price = 0.45 + $rand() * 0.1;
    $candles = [];
    $min = 1.0;
    $max = 0.0;
    for ($i = 0; $i < $count; $i++) {
        $open = $price;
        $close = min(0.95, max(0.05, $open + ($rand() - 0.47) * 0.09));
        $high = min(0.99, max($open, $close) + $rand() * 0.04);
        $low = max(0.01, min($open, $close) - $rand() * 0.04);
        $candles[] = [$open, $close, $high, $low];
        $min = min($min, $low);
        $max = max($max, $high);
        $price = $close;
    }
    $range = max(0.0001, $max - $min);
    $y = static fn (float $v): float => $pad + (1 - (($v - $min) / $range)) * $plotH;

    // Light, editorial palette: category colour up-candles, neutral grey down-candles.
    $down = '#aab2bd';
    $body = '';
    $maPoints = [];
    $window = [];
    $lastY = $pad + $plotH / 2;
    foreach ($candles as $i => [$open, $close, $high, $low]) {
        $cx = $pad + $slot * $i + $slot / 2;
        $c = $close >= $open ? $color : $down;
        $top = min($y($open), $y($close));
        $h = max(1.5, abs($y($close) - $y($open)));
        $body .= sprintf(
            '<line x1="%.1f" y1="%.1f" x2="%.1f" y2="%.1f" stroke="%s" stroke-width="1.1"/>',
            $cx, $y($high), $cx, $y($low), $c
        );
        $body .= sprintf(
            '<rect x="%.1f" y="%.1f" width="%.1f" height="%.1f" fill="%s"/>',
            $cx - $bodyW / 2, $top, $bodyW, $h, $c
        );

        $window[] = $close;
        if (count($window) > 5) {
            array_shift($window);
        }
        $lastY = $y(array_sum($window) / count($window));
        $maPoints[] = sprintf('%.1f,%.1f', $cx, $lastY);
    }

    // Hairline horizontal grid.
    $grid = '';
    for ($g = 1; $g <= 3; $g++) {
        $gy = $pad + $plotH * $g / 4;
        $grid .= sprintf(
            '<line x1="%d" y1="%.1f" x2="%d" y2="%.1f" stroke="#e7e9ec" stroke-width="1"/>',
            $pad, $gy, $width - $pad, $gy
        );
    }
    // Baseline axis.
    $axisY = $pad + $plotH;
    $grid .= sprintf('<line x1="%d" y1="%.1f" x2="%d" y2="%.1f" stroke="#d6d9de" stroke-width="1"/>', $pad, $axisY, $width - $pad, $axisY);

    $maPath = 'M' . implode(' L', $maPoints);
    $areaPath = $maPath . sprintf(' L%.1f,%.1f L%.1f,%.1f Z', $width - $pad, $axisY, $pad + $slot / 2, $axisY);
    $lastX = $width - $pad;
    $uid = substr(md5($seed), 0, 6);

    return <<<SVG
<svg viewBox="0 0 {$width} {$height}" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Chart illustration">
  <defs>
    <linearGradient id="bg{$uid}" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="#ffffff"/><stop offset="1" stop-color="#f3f4f6"/>
    </linearGradient>
    <linearGradient id="ar{$uid}" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0" stop-color="{$color}" stop-opacity="0.12"/><stop offset="1" stop-color="{$color}" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <rect width="{$width}" height="{$height}" fill="url(#bg{$uid})"/>
  {$grid}
  <path d="{$areaPath}" fill="url(#ar{$uid})"/>
  {$body}
  <path d="{$maPath}" fill="none" stroke="{$color}" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
  <line x1="{$lastX}" y1="{$lastY}" x2="{$pad}" y2="{$lastY}" stroke="{$color}" stroke-width="0.8" stroke-dasharray="3 3" opacity="0.5"/>
  <circle cx="{$lastX}" cy="{$lastY}" r="3" fill="{$color}"/>
</svg>
SVG;
}

/** Generated chart artwork — the fallback when an article has no photo. */
function article_cover(array $article, int $width = 480, int $height = 260): string
{
    return cd_chart_svg(
        (string) ($article['slug'] ?? ''),
        category_color((string) ($article['category_slug'] ?? '')),
        $width,
        $height
    );
}

/** Cover media: the downloaded photo if present, otherwise the SVG chart. */
function cover_media(array $a, int $width = 480, int $height = 270): string
{
    if (!empty($a['image'])) {
        return '<img src="' . e((string) $a['image']) . '" alt="" loading="lazy" decoding="async"'
            . ' width="' . $width . '" height="' . $height . '">';
    }

    return article_cover($a, $width, $height);
}

/** Attribution line for a photo (Creative Commons requires credit). */
function image_credit_html(array $a): string
{
    if (empty($a['image']) || empty($a['image_credit'])) {
        return '';
    }

    $creator = e((string) $a['image_credit']);
    if (!empty($a['image_credit_url'])) {
        $creator = '<a href="' . e((string) $a['image_credit_url']) . '" rel="noopener nofollow" target="_blank">' . $creator . '</a>';
    }

    $license = '';
    if (!empty($a['image_license'])) {
        $label = 'CC ' . strtoupper((string) $a['image_license']);
        $license = !empty($a['image_license_url'])
            ? ' · <a href="' . e((string) $a['image_license_url']) . '" rel="noopener nofollow" target="_blank">' . e($label) . '</a>'
            : ' · ' . e($label);
    }

    $source = !empty($a['image_source']) ? ' / ' . e(ucfirst((string) $a['image_source'])) : '';

    return '<p class="cover-credit">Photo: ' . $creator . $source . $license . '</p>';
}

/** Reusable article card (used on home, category, search and related lists). */
function article_card(array $a): string
{
    $cover = cover_media($a, 480, 270);
    $color = category_color((string) $a['category_slug']);
    $slug = e((string) $a['slug']);
    $catSlug = e((string) $a['category_slug']);
    $meta = e((string) $a['author'])
        . ' · <time datetime="' . e(iso_date((string) $a['published_at'])) . '">'
        . e(time_ago((string) $a['published_at'])) . '</time>'
        . ' · ' . (int) $a['read_minutes'] . ' min read';

    // The cover repeats the headline link; hide it from AT/keyboard so the
    // headline link is the single accessible link to the article.
    return '<article class="card">'
        . '<a class="card-cover" href="/article/' . $slug . '" aria-hidden="true" tabindex="-1">' . $cover . '</a>'
        . '<div class="card-body">'
        . '<a class="chip" href="/category/' . $catSlug . '" style="--chip:' . $color . '">'
        . e((string) $a['category_name']) . '</a>'
        . '<h3><a href="/article/' . $slug . '">' . e((string) $a['title']) . '</a></h3>'
        . '<p class="excerpt">' . e((string) $a['excerpt']) . '</p>'
        . '<p class="meta">' . $meta . '</p>'
        . '</div></article>';
}

/** Sidebar block: most-read list plus category links. */
function render_sidebar(array $popular, array $nav = []): string
{
    $html = '<aside class="sidebar">';

    $html .= '<section class="widget"><h2>Most read</h2><ol class="ranklist">';
    foreach ($popular as $p) {
        $html .= '<li><a href="/article/' . e((string) $p['slug']) . '">' . e((string) $p['title']) . '</a>'
            . '<span class="ranklist-meta">' . format_views((int) $p['views']) . ' views</span></li>';
    }
    $html .= '</ol></section>';

    if ($nav !== []) {
        $html .= '<section class="widget"><h2>Topics</h2><ul class="taglist">';
        foreach ($nav as $c) {
            $html .= '<li><a href="/category/' . e((string) $c['slug']) . '" style="--chip:'
                . category_color((string) $c['slug']) . '">' . e((string) $c['name'])
                . ' <span>' . (int) ($c['article_count'] ?? 0) . '</span></a></li>';
        }
        $html .= '</ul></section>';
    }

    $html .= '<section class="widget newsletter"><h2>The Desk, weekly</h2>'
        . '<p>One email, every Monday: the tools and tactics that actually shipped.</p>'
        . '<form onsubmit="return false"><input type="email" name="email" autocomplete="email" placeholder="you@desk.io" aria-label="Email">'
        . '<button type="submit">Subscribe</button></form></section>';

    return $html . '</aside>';
}
