<?php

declare(strict_types=1);

/**
 * Seed content for CryptoDesk.
 *
 * The 100 target keywords are mapped to editorial categories, and an article
 * is generated for each one. Generation is deterministic (seeded by the
 * keyword index) so reseeding always produces the same blog.
 */

/** @return array<string, array{name:string, tagline:string, description:string}> */
function cd_categories(): array
{
    return [
        'trading-platforms' => [
            'name' => 'Trading Platforms',
            'tagline' => 'Execution & market access',
            'description' => 'Exchanges, terminals, order routing and the plumbing that gets your orders filled.',
        ],
        'technical-analysis' => [
            'name' => 'Technical Analysis',
            'tagline' => 'Charts, indicators & signals',
            'description' => 'Indicators, chart patterns and the math behind reading price action.',
        ],
        'defi-liquidity' => [
            'name' => 'DeFi & Liquidity',
            'tagline' => 'AMMs, pools & yield',
            'description' => 'Automated market makers, liquidity provision, staking and on-chain yield.',
        ],
        'on-chain-analytics' => [
            'name' => 'On-Chain Analytics',
            'tagline' => 'Reading the blockchain',
            'description' => 'Dashboards, explorers and metrics that turn raw chain data into signal.',
        ],
        'portfolio-risk' => [
            'name' => 'Portfolio & Risk',
            'tagline' => 'Allocation & drawdown control',
            'description' => 'Position sizing, rebalancing and the risk math that keeps accounts alive.',
        ],
        'automation-apis' => [
            'name' => 'Automation & APIs',
            'tagline' => 'Bots, SDKs & data feeds',
            'description' => 'Trading bots, backtesting, market-data APIs and the SDKs that wire them up.',
        ],
        'wallets-security' => [
            'name' => 'Wallets & Security',
            'tagline' => 'Custody & key management',
            'description' => 'Hardware wallets, multisig, cold storage and custody for serious balances.',
        ],
    ];
}

/**
 * The 100 keywords, each mapped to a category slug.
 *
 * @return array<int, array{0:string, 1:string}>
 */
function cd_keywords(): array
{
    return [
        ['Trading terminal', 'trading-platforms'],
        ['Charting software', 'technical-analysis'],
        ['Order management system', 'trading-platforms'],
        ['Portfolio tracker', 'portfolio-risk'],
        ['Price alerts', 'trading-platforms'],
        ['Market analysis tool', 'technical-analysis'],
        ['Algo trading platform', 'automation-apis'],
        ['Moving averages indicator', 'technical-analysis'],
        ['RSI oscillator', 'technical-analysis'],
        ['MACD histogram', 'technical-analysis'],
        ['Bollinger bands settings', 'technical-analysis'],
        ['Fibonacci retracement levels', 'technical-analysis'],
        ['Volume profile chart', 'technical-analysis'],
        ['Bid ask spread tracker', 'trading-platforms'],
        ['Order book depth map', 'trading-platforms'],
        ['Smart contract audit tool', 'wallets-security'],
        ['On chain analytics dashboard', 'on-chain-analytics'],
        ['Blockchain data API', 'on-chain-analytics'],
        ['Gas fee calculator', 'on-chain-analytics'],
        ['Block explorer analytics', 'on-chain-analytics'],
        ['Multi exchange trading interface', 'trading-platforms'],
        ['Spot market aggregator', 'trading-platforms'],
        ['Futures contract platform', 'trading-platforms'],
        ['Perpetual swap exchange', 'trading-platforms'],
        ['Margin trading tools', 'trading-platforms'],
        ['Funding rate comparison', 'trading-platforms'],
        ['Open interest tracker', 'trading-platforms'],
        ['Long short ratio monitor', 'trading-platforms'],
        ['Volume weighted average price', 'technical-analysis'],
        ['Liquidity pool provider', 'defi-liquidity'],
        ['Automated market maker AMM', 'defi-liquidity'],
        ['Token swap aggregator', 'defi-liquidity'],
        ['Yield farming calculator', 'defi-liquidity'],
        ['Staking rewards dashboard', 'defi-liquidity'],
        ['LP token optimizer', 'defi-liquidity'],
        ['Impermanent loss protection', 'defi-liquidity'],
        ['Decentralized exchange volume', 'defi-liquidity'],
        ['Cross chain bridge aggregator', 'defi-liquidity'],
        ['Wrapped asset converter', 'defi-liquidity'],
        ['Stablecoin liquidity pools', 'defi-liquidity'],
        ['Dollar cost averaging tool', 'portfolio-risk'],
        ['Rebalancing portfolio manager', 'portfolio-risk'],
        ['Grid trading bot setup', 'automation-apis'],
        ['Copy trading platform interface', 'automation-apis'],
        ['Signal based trade automation', 'automation-apis'],
        ['Strategy backtesting engine', 'automation-apis'],
        ['Risk management calculator', 'portfolio-risk'],
        ['Position sizing optimizer', 'portfolio-risk'],
        ['Maximum drawdown tracker', 'portfolio-risk'],
        ['Sharpe ratio analytics', 'portfolio-risk'],
        ['Technical analysis scanner', 'technical-analysis'],
        ['Candlestick pattern recognition', 'technical-analysis'],
        ['Volume spike alert system', 'technical-analysis'],
        ['Support resistance levels', 'technical-analysis'],
        ['Trend line drawing tool', 'technical-analysis'],
        ['Multi timeframe chart analysis', 'technical-analysis'],
        ['Real time market data feed', 'automation-apis'],
        ['WebSocket price streaming', 'automation-apis'],
        ['API trading interface', 'automation-apis'],
        ['Custom indicator builder', 'technical-analysis'],
        ['Order execution speed metrics', 'trading-platforms'],
        ['Slippage reduction tools', 'trading-platforms'],
        ['High frequency trading platform', 'trading-platforms'],
        ['Market making infrastructure', 'trading-platforms'],
        ['Trading volume heatmap', 'technical-analysis'],
        ['Exchange liquidity ranking', 'trading-platforms'],
        ['Token unlock schedule tracker', 'on-chain-analytics'],
        ['Governance proposal monitor', 'on-chain-analytics'],
        ['Bitcoin dominance chart', 'on-chain-analytics'],
        ['Ethereum gas history graph', 'on-chain-analytics'],
        ['Altcoin market cap analysis', 'on-chain-analytics'],
        ['Trading fee comparison tool', 'trading-platforms'],
        ['API rate limit dashboard', 'automation-apis'],
        ['Node performance monitor', 'on-chain-analytics'],
        ['Chain reorganization tracker', 'on-chain-analytics'],
        ['Transaction confirmation estimator', 'on-chain-analytics'],
        ['Smart contract deployment tool', 'wallets-security'],
        ['Hardware wallet management', 'wallets-security'],
        ['Multi sig wallet setup', 'wallets-security'],
        ['Cold storage security system', 'wallets-security'],
        ['Hot wallet balancing tool', 'wallets-security'],
        ['Portfolio correlation matrix', 'portfolio-risk'],
        ['Asset allocation rebalancer', 'portfolio-risk'],
        ['Cross exchange portfolio tracker', 'portfolio-risk'],
        ['Multi currency position manager', 'portfolio-risk'],
        ['MetaTrader integration plugin', 'automation-apis'],
        ['TradingView strategy automation', 'automation-apis'],
        ['Python trading bot framework', 'automation-apis'],
        ['JavaScript SDK for exchanges', 'automation-apis'],
        ['REST API market data', 'automation-apis'],
        ['Historical tick data analysis', 'automation-apis'],
        ['Volatility index calculator', 'technical-analysis'],
        ['ATR indicator settings', 'technical-analysis'],
        ['VWAP execution tracker', 'technical-analysis'],
        ['Order flow analytics tool', 'trading-platforms'],
        ['Market microstructure dashboard', 'trading-platforms'],
        ['Institutional trading terminal', 'trading-platforms'],
        ['Custody solution provider', 'wallets-security'],
        ['Settlement service infrastructure', 'wallets-security'],
        ['Clearing house risk management', 'portfolio-risk'],
    ];
}

/** Turn a keyword into a URL-safe slug. */
function cd_slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}

/** Deterministically pick one element of $list, seeded by $seed. */
function cd_pick(array $list, int $seed): mixed
{
    return $list[$seed % count($list)];
}

/**
 * Lowercase a keyword for mid-sentence use, but keep a leading acronym
 * uppercase ("RSI oscillator" stays "RSI oscillator", not "rSI oscillator").
 */
function cd_lower_kw(string $keyword): string
{
    $first = strtok($keyword, ' ');
    if ($first !== false && strlen($first) > 1 && $first === strtoupper($first) && ctype_alpha($first)) {
        return $keyword;
    }

    return lcfirst($keyword);
}

/** Does $word start with a vowel *sound* (handles letter-spelled acronyms)? */
function cd_starts_vowel_sound(string $word): bool
{
    $w = ltrim($word, "\"'“");
    if ($w === '') {
        return false;
    }

    // Acronyms pronounced as a word, not letter-by-letter (judge by first letter).
    static $spokenAsWord = ['REST' => true, 'RING' => true];
    $head = preg_match('/^[A-Z]{2,}/', $w, $m) ? $m[0] : '';

    if ($head !== '' && !isset($spokenAsWord[$head])) {
        // Letters whose spoken name begins with a vowel sound: A E F H I L M N O R S X.
        return (bool) preg_match('/^[AEFHILMNORSX]/', $w);
    }

    return (bool) preg_match('/^[aeiouAEIOU]/', $w);
}

/** Promote "a" → "an" before a vowel sound. Safe to run over prose + plain HTML. */
function cd_fix_articles(string $text): string
{
    return (string) preg_replace_callback(
        '/\b([Aa])\b(\s+)(\S+)/',
        static fn (array $m): string => cd_starts_vowel_sound($m[3]) ? $m[1] . 'n' . $m[2] . $m[3] : $m[0],
        $text
    );
}

/**
 * Generate the full article record for a keyword.
 *
 * @param array{0:string, 1:string} $kw
 * @return array<string, mixed>
 */
function cd_generate_article(array $kw, int $index): array
{
    [$keyword, $categorySlug] = $kw;
    $cats = cd_categories();
    $category = $cats[$categorySlug];

    $lower = cd_lower_kw($keyword);
    $seed = $index + 1;

    $authors = [
        'Maya Chen', 'Daniel Reyes', 'Priya Nair', 'Tomás Albrecht',
        'Lena Volkov', 'Marcus Bell', 'Sofia Diaz', 'Kenji Watanabe',
    ];

    $headlines = [
        "%s: a practical guide for 2026",
        "How a %s fits into a modern trading stack",
        "Choosing a %s without overpaying",
        "Inside the %s: what actually moves the needle",
        "%s, explained for serious traders",
        "The state of the %s in 2026",
        "%s: the features that matter and the ones that don't",
        "What we learned shipping a %s to a live desk",
    ];

    $leads = [
        "Every desk eventually argues about its %s, and for good reason — it sits on the critical path between an idea and a filled order.",
        "Ask ten traders about the ideal %s and you will get eleven answers. Here is the framework we use to cut through the noise.",
        "The %s has quietly become table stakes, but most teams still evaluate it on the wrong criteria.",
        "A %s looks simple on a marketing page and turns out to be anything but once real volume hits it.",
        "If you only fix one part of your workflow this quarter, a properly chosen %s is a strong candidate.",
    ];

    $angles = [
        'trading-platforms' => [
            'domain' => 'execution and market access',
            'why' => "When spreads widen and order books thin out, the gap between a good and a mediocre %s shows up directly in your fill prices.",
            'bullets' => [
                'Latency and uptime during the most volatile sessions, not the calm ones',
                'Breadth of supported venues, instruments and order types',
                'Fee tiers, maker rebates and how they scale with volume',
                'Built-in risk controls: position limits, kill switches, max-order checks',
                'API parity — anything the UI can do, the API should do too',
            ],
        ],
        'technical-analysis' => [
            'domain' => 'reading price action',
            'why' => "A %s is only as useful as your discipline around it; the same signal that prints money in a trend will bleed you dry in a range.",
            'bullets' => [
                'Whether the calculation matches the textbook definition exactly',
                'How it behaves on low-liquidity assets and gappy data',
                'Configurable lookback periods and smoothing options',
                'Repainting behaviour — does the signal change after the candle closes?',
                'How cleanly it composes with the rest of your chart',
            ],
        ],
        'defi-liquidity' => [
            'domain' => 'on-chain liquidity',
            'why' => "In DeFi the %s does not just report numbers — it changes your actual yield and risk the moment you deposit.",
            'bullets' => [
                'Whether quoted APRs are net of fees, gas and impermanent loss',
                'Smart-contract audit history and time-tested TVL',
                'How slippage scales with trade size against pool depth',
                'Exit liquidity — can you actually get out at scale?',
                'Cross-chain assumptions and bridge risk baked into the numbers',
            ],
        ],
        'on-chain-analytics' => [
            'domain' => 'turning chain data into signal',
            'why' => "Raw chain data is noisy; a good %s earns its keep by being right about which numbers you can trust.",
            'bullets' => [
                'Data freshness and how far behind the chain tip it runs',
                'Node and indexer reliability behind the dashboard',
                'How reorgs and orphaned blocks are handled',
                'Whether metrics are reproducible from public data',
                'Export and API access so you are not locked into one UI',
            ],
        ],
        'portfolio-risk' => [
            'domain' => 'allocation and drawdown control',
            'why' => "A %s is the difference between a bad week and a blown account; the math is boring right up until it is the only thing that matters.",
            'bullets' => [
                'Whether it models correlation, not just per-asset volatility',
                'How it treats leverage and cross-margin exposure',
                'Realistic assumptions — no survivorship bias in the backtest',
                'Clear, auditable position-sizing rules',
                'Alerts that fire before a limit is breached, not after',
            ],
        ],
        'automation-apis' => [
            'domain' => 'automation and integration',
            'why' => "Automation amplifies whatever you feed it, so a %s magnifies good logic and bad logic with equal enthusiasm.",
            'bullets' => [
                'Rate limits, and how gracefully the client backs off',
                'Reconnection and gap-recovery on dropped connections',
                'Idempotency on order placement to avoid duplicate fills',
                'Quality of the SDK docs and example code',
                'A realistic sandbox or paper-trading environment',
            ],
        ],
        'wallets-security' => [
            'domain' => 'custody and key management',
            'why' => "With a %s the failure mode is not a bad trade — it is a permanent, irreversible loss of funds, so the bar is much higher.",
            'bullets' => [
                'Where private keys live and who can ever touch them',
                'Recovery paths that survive a lost device or a dead signer',
                'Independent audits and a track record under real load',
                'Clear separation between hot operational funds and cold reserves',
                'Approval workflows that require more than one human',
            ],
        ],
    ];

    $angle = $angles[$categorySlug];

    $definitions = [
        "At its core, a %s solves one job: %s. Everything else — the dashboards, the integrations, the marketing — hangs off that single responsibility.",
        "Strip away the branding and a %s is really a tool for %s. Judge it on how well it does that before anything else.",
        "Think of a %s as the layer that owns %s. When it works you forget it exists; when it fails, you feel it immediately.",
    ];

    $closings = [
        "There is no universally \"best\" %s — only the one that matches your size, your style and the markets you actually trade. Start from your constraints, not the feature list.",
        "Pick the %s you understand well enough to debug at 3 a.m. during a market event. Cleverness you cannot reason about is a liability, not an edge.",
        "The right %s fades into the background and lets you focus on decisions that actually carry edge. If you are fighting the tool, you have the wrong one.",
        "Run any %s in paper or at tiny size first. The marketing page never mentions the failure modes — your own logs will.",
    ];

    $headline = cd_fix_articles(sprintf(cd_pick($headlines, $seed), $keyword));
    $lead = sprintf(cd_pick($leads, $seed * 3), $lower);
    $definition = sprintf(cd_pick($definitions, $seed * 5), $lower, $angle['domain']);
    $why = sprintf($angle['why'], $lower);
    $closing = sprintf(cd_pick($closings, $seed * 7), $lower);

    $bulletsHtml = '';
    foreach ($angle['bullets'] as $b) {
        $bulletsHtml .= '            <li>' . htmlspecialchars($b, ENT_QUOTES) . "</li>\n";
    }

    $body = <<<HTML
    <p>{$lead}</p>

    <h2>What a {$lower} actually does</h2>
    <p>{$definition}</p>
    <p>{$why}</p>

    <h2>What to look for</h2>
    <p>When you put a {$lower} through its paces, weigh it against the things that bite in production rather than the ones that demo well:</p>
    <ul>
    {$bulletsHtml}    </ul>

    <h2>Common mistakes</h2>
    <p>The usual trap is optimising for the happy path. A {$lower} that looks great on a quiet Tuesday can fall apart the moment volume, volatility or fees spike — which is exactly when you need it most. Test it under stress, with adversarial inputs, and on the messiest data you can find.</p>

    <h2>The bottom line</h2>
    <p>{$closing}</p>
    HTML;

    // Fix "a"→"an" across the prose.
    $body = cd_fix_articles($body);

    // Spread publish dates backwards from the seed reference date.
    $base = strtotime('2026-05-27 09:00:00') ?: time();
    $minutesBack = ($index * 1700) + (($seed * 37) % 1200);
    $publishedAt = date('Y-m-d H:i:s', $base - ($minutesBack * 60));

    $wordCount = str_word_count(strip_tags($body));
    $readMinutes = max(3, (int) ceil($wordCount / 200) + 2);
    $views = (($seed * 7919) % 9100) + 180;

    $excerpts = [
        "We break down what a %s does, where it shines, and how to size one up before you commit.",
        "A no-nonsense look at the %s — the metrics that matter and the traps that don't make the brochure.",
        "From first principles to the order ticket: the short version of everything you need to know about the %s.",
        "What a %s gets right, what it quietly gets wrong, and how to tell the difference on your own desk.",
    ];
    $excerpt = cd_fix_articles(sprintf(cd_pick($excerpts, $seed * 11), $lower));

    return [
        'category_slug' => $categorySlug,
        'slug' => cd_slugify($keyword),
        'title' => $headline,
        'keyword' => $keyword,
        'excerpt' => $excerpt,
        'body' => $body,
        'author' => cd_pick($authors, $seed),
        'read_minutes' => $readMinutes,
        'views' => $views,
        'published_at' => $publishedAt,
    ];
}
