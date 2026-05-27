#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Download one real, openly-licensed cover photo per article from Openverse
 * (https://openverse.org) — a Creative Commons / public-domain image
 * aggregator that needs no API key.
 *
 * Saves files to public/assets/covers/<slug>.<ext> and writes an attribution
 * manifest to data/covers.json. The seeder reads that manifest; if it is
 * missing, articles fall back to the generated SVG chart, so the site still
 * runs with zero setup.
 *
 *   php bin/fetch_images.php          # fetch any missing covers
 *   php bin/fetch_images.php --force  # re-fetch everything
 */

$root = dirname(__DIR__);
require $root . '/src/content.php';

const OV_ENDPOINT = 'https://api.openverse.org/v1/images/';
const UA = 'CryptoDesk/1.0 (editorial demo; +https://localhost)';
const API_PAUSE = 4;        // seconds between API calls (anon burst = 20/min)
const MAX_BYTES = 4_500_000; // skip oversized originals
const MAX_PER_CREATOR = 1;  // one image per creator — kills near-duplicate photosets
const VALID_EXT = ['jpg', 'jpeg', 'png', 'webp'];

$force = in_array('--force', $argv, true);

$coversDir = $root . '/public/assets/covers';
if (!is_dir($coversDir)) {
    mkdir($coversDir, 0775, true);
}

// Search terms per category — topical, but broad enough to return CC photos.
$queries = [
    'trading-platforms' => ['stock market', 'stock exchange', 'financial chart', 'forex trading', 'stock market screen'],
    'technical-analysis' => ['stock chart', 'candlestick chart', 'financial graph', 'market analysis chart', 'trading chart'],
    'defi-liquidity' => ['cryptocurrency coins', 'ethereum coin', 'decentralized finance', 'crypto token', 'litecoin'],
    'on-chain-analytics' => ['blockchain', 'bitcoin network', 'data analytics dashboard', 'server data center', 'network nodes'],
    'portfolio-risk' => ['stack of coins money', 'piggy bank savings', 'financial calculator', 'dollar bills cash', 'bull stock market'],
    'automation-apis' => ['computer programming code', 'software developer screen', 'server room', 'data center network', 'source code'],
    'wallets-security' => ['cryptocurrency wallet', 'hardware security key', 'digital security lock', 'padlock cyber security', 'safe vault'],
];

// Categories where broad terms attract off-topic photos: keep only results whose
// title actually looks finance-related.
$titleFilters = [
    'trading-platforms' => '/(stock|market|exchange|trad|financ|forex|nasdaq|dow|ticker|bull market|bear market|invest|wall street)/i',
];

/** GET JSON from the Openverse API. */
function ov_search(string $query, int $page): array
{
    $url = OV_ENDPOINT . '?' . http_build_query([
        'q' => $query,
        'page_size' => 20,
        'page' => $page,
        'mature' => 'false',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => UA,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false || $code !== 200) {
        fwrite(STDERR, "  ! search failed ({$code}) for \"{$query}\" p{$page}\n");
        return [];
    }

    return json_decode((string) $body, true)['results'] ?? [];
}

/** Extension from a URL path, lower-cased, or '' if unusable. */
function url_ext(string $url): string
{
    $path = (string) parse_url($url, PHP_URL_PATH);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return in_array($ext, VALID_EXT, true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : '';
}

/** Download an image to disk; returns true on success. */
function download(string $url, string $dest): bool
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_USERAGENT => UA,
    ]);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($data === false || $code !== 200 || !str_starts_with($type, 'image/')) {
        return false;
    }
    if (strlen((string) $data) > MAX_BYTES || strlen((string) $data) < 2000) {
        return false;
    }

    return file_put_contents($dest, $data) !== false;
}

// Group article slugs by category, preserving keyword order.
$slugsByCat = [];
foreach (cd_keywords() as $kw) {
    [$keyword, $cat] = $kw;
    $slugsByCat[$cat][] = cd_slugify($keyword);
}

$manifestPath = $root . '/data/covers.json';
$manifest = (!$force && is_file($manifestPath)) ? (json_decode((string) file_get_contents($manifestPath), true) ?: []) : [];

$saved = 0;
$skipped = 0;
$hashes = []; // md5 of every saved cover, to reject byte-identical duplicates

foreach ($slugsByCat as $cat => $slugs) {
    $needed = count($slugs);

    // Skip categories that are already complete (avoids needless API calls).
    if (!$force) {
        $complete = true;
        foreach ($slugs as $s) {
            if (empty($manifest[$s]['file']) || !is_file($root . '/public' . $manifest[$s]['file'])) {
                $complete = false;
                break;
            }
        }
        if ($complete) {
            $skipped += $needed;
            continue;
        }
    }

    fwrite(STDOUT, "\n[{$cat}] need {$needed} covers\n");

    // Build a candidate pool from the category's search terms.
    $filter = $titleFilters[$cat] ?? null;
    $target = $needed + 15;
    $pool = [];
    $seen = [];
    $creatorCount = [];
    foreach ($queries[$cat] as $query) {
        if (count($pool) >= $target) {
            break;
        }
        for ($page = 1; $page <= 3; $page++) {
            $results = ov_search($query, $page);
            sleep(API_PAUSE);
            foreach ($results as $r) {
                $url = $r['url'] ?? '';
                $id = $r['id'] ?? $url;
                if ($url === '' || isset($seen[$id]) || url_ext($url) === '') {
                    continue;
                }
                if ($filter !== null && !preg_match($filter, (string) ($r['title'] ?? ''))) {
                    continue;
                }
                // Cap images per creator so one photoset can't dominate.
                $creator = strtolower(trim((string) ($r['creator'] ?? '')));
                if ($creator !== '') {
                    if (($creatorCount[$creator] ?? 0) >= MAX_PER_CREATOR) {
                        continue;
                    }
                    $creatorCount[$creator] = ($creatorCount[$creator] ?? 0) + 1;
                }
                $seen[$id] = true;
                $pool[] = $r;
            }
            if ($results === [] || count($pool) >= $target) {
                break;
            }
        }
    }
    fwrite(STDOUT, "  pool: " . count($pool) . " candidates\n");

    // Assign + download.
    $cursor = 0;
    foreach ($slugs as $slug) {
        if (!$force && isset($manifest[$slug]) && is_file($root . '/public' . ($manifest[$slug]['file'] ?? ''))) {
            $skipped++;
            continue;
        }

        $done = false;
        while ($cursor < count($pool)) {
            $r = $pool[$cursor++];
            $ext = url_ext($r['url']);
            $rel = "/assets/covers/{$slug}.{$ext}";
            $dest = $root . '/public' . $rel;
            if (download($r['url'], $dest)) {
                // Reject byte-identical duplicates of an already-saved cover.
                $hash = md5_file($dest);
                if ($hash !== false && isset($hashes[$hash])) {
                    @unlink($dest);
                    continue;
                }
                $hashes[$hash] = true;
                $manifest[$slug] = [
                    'file' => $rel,
                    'title' => $r['title'] ?? '',
                    'creator' => $r['creator'] ?? '',
                    'creator_url' => $r['creator_url'] ?? '',
                    'license' => $r['license'] ?? '',
                    'license_version' => $r['license_version'] ?? '',
                    'license_url' => $r['license_url'] ?? '',
                    'source' => $r['source'] ?? '',
                    'landing_url' => $r['foreign_landing_url'] ?? '',
                ];
                $saved++;
                $done = true;
                fwrite(STDOUT, "  ✓ {$slug} ← " . substr((string) ($r['title'] ?: $r['url']), 0, 48) . "\n");
                break;
            }
        }
        if (!$done) {
            fwrite(STDERR, "  ✗ {$slug}: no usable image in pool\n");
        }
    }

    file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

fwrite(STDOUT, "\nDone. Saved {$saved}, skipped {$skipped} existing. Manifest: data/covers.json\n");
