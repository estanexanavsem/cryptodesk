<?php

declare(strict_types=1);

/**
 * Seed content for the blog.
 *
 * The 100 target keywords are mapped to editorial categories, and an article
 * is generated for each one. Generation is deterministic (seeded by the
 * keyword index) so reseeding always produces the same blog.
 */

/** @return array<string, array{name:string, tagline:string, description:string}> */
function cd_categories(): array
{
    return [
        'rigs-hardware' => [
            'name' => 'Rigs & Hardware',
            'tagline' => 'Miners, cards & components',
            'description' => 'Rigs, GPU and ASIC miners, boards and the parts that turn power into hashes.',
        ],
        'cooling-power' => [
            'name' => 'Cooling & Power',
            'tagline' => 'Thermals, power & efficiency',
            'description' => 'Cooling, airflow, power supplies and the electricity math that decides whether a rig pays.',
        ],
        'facilities-builds' => [
            'name' => 'Facilities & Builds',
            'tagline' => 'Racks, space & scale',
            'description' => 'Racks, enclosures, containers and the sites that house rigs from one unit to thousands.',
        ],
        'hosting-services' => [
            'name' => 'Hosting & Services',
            'tagline' => 'Managed, hosted & maintained',
            'description' => 'Hosting, managed operations, maintenance and the suppliers behind a running fleet.',
        ],
        'network-validation' => [
            'name' => 'Network & Validation',
            'tagline' => 'Proof, consensus & ledgers',
            'description' => 'Proof of work, consensus, validation and the compute that secures a distributed network.',
        ],
        'performance-roi' => [
            'name' => 'Performance & ROI',
            'tagline' => 'Efficiency & payback',
            'description' => 'Hashrate per watt, tuning, profitability and the numbers that decide what is worth running.',
        ],
        'getting-started' => [
            'name' => 'Getting Started',
            'tagline' => 'First rigs & home setups',
            'description' => 'Beginner guides, home and DIY builds, and getting a first small setup running.',
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
        ['Mining rig', 'rigs-hardware'],
        ['GPU miner', 'rigs-hardware'],
        ['ASIC miner', 'rigs-hardware'],
        ['Mining card', 'rigs-hardware'],
        ['Graphics card for mining', 'rigs-hardware'],
        ['Mining motherboard', 'rigs-hardware'],
        ['Mining power supply', 'cooling-power'],
        ['Mining fan', 'cooling-power'],
        ['Mining rack', 'facilities-builds'],
        ['Mining enclosure', 'facilities-builds'],
        ['Mining chassis', 'facilities-builds'],
        ['Mining heatsink', 'cooling-power'],
        ['Mining riser cable', 'rigs-hardware'],
        ['Mining USB controller', 'rigs-hardware'],
        ['Mining firmware', 'rigs-hardware'],
        ['Proof of work', 'network-validation'],
        ['Hash rate calculator', 'performance-roi'],
        ['Compute power', 'network-validation'],
        ['Block reward', 'network-validation'],
        ['Mining difficulty', 'network-validation'],
        ['Mining pool share', 'network-validation'],
        ['Digital currency computing', 'network-validation'],
        ['Distributed ledger processing', 'network-validation'],
        ['Blockchain validation service', 'network-validation'],
        ['Network consensus algorithm', 'network-validation'],
        ['Transaction verification', 'network-validation'],
        ['Computational validation', 'network-validation'],
        ['Cloud mining service', 'hosting-services'],
        ['Managed mining', 'hosting-services'],
        ['Remote mining operation', 'hosting-services'],
        ['Mining hosting', 'hosting-services'],
        ['Dedicated mining server', 'hosting-services'],
        ['Mining as a service', 'hosting-services'],
        ['Mining rental program', 'hosting-services'],
        ['Turnkey mining solution', 'hosting-services'],
        ['Mobile mining unit', 'facilities-builds'],
        ['Containerized mining facility', 'facilities-builds'],
        ['Mining electricity cost', 'cooling-power'],
        ['Energy efficient mining', 'cooling-power'],
        ['Renewable energy mining', 'cooling-power'],
        ['Solar powered miner', 'cooling-power'],
        ['Industrial power for miners', 'cooling-power'],
        ['Mining cooling solution', 'cooling-power'],
        ['Liquid cooling for mining', 'cooling-power'],
        ['Air cooled mining rig', 'cooling-power'],
        ['Heat recovery mining', 'cooling-power'],
        ['Low voltage mining setup', 'cooling-power'],
        ['Mining efficiency calculator', 'performance-roi'],
        ['Optimal GPU configuration', 'performance-roi'],
        ['Overclocking for mining', 'performance-roi'],
        ['Power draw optimization', 'performance-roi'],
        ['Mining profitability tool', 'performance-roi'],
        ['Return on investment calculator', 'performance-roi'],
        ['Cost per hash calculation', 'performance-roi'],
        ['Maintenance schedule for miners', 'hosting-services'],
        ['How to start mining at home', 'getting-started'],
        ['Beginner mining guide', 'getting-started'],
        ['DIY mining setup', 'getting-started'],
        ['Home mining station', 'getting-started'],
        ['Office based computing unit', 'getting-started'],
        ['Garage mining project', 'getting-started'],
        ['Small scale operation', 'getting-started'],
        ['Scalable computing infrastructure', 'facilities-builds'],
        ['Mining equipment supplier', 'hosting-services'],
        ['Wholesale mining hardware', 'hosting-services'],
        ['Certified refurbished miners', 'rigs-hardware'],
        ['New generation processors', 'rigs-hardware'],
        ['High performance chipsets', 'rigs-hardware'],
        ['Modular design units', 'rigs-hardware'],
        ['Interchangeable components', 'rigs-hardware'],
        ['Miner maintenance service', 'hosting-services'],
        ['Firmware update program', 'hosting-services'],
        ['Monitoring dashboard for mining', 'hosting-services'],
        ['Automated temperature control', 'cooling-power'],
        ['Noise reduction system', 'cooling-power'],
        ['Dust filter upgrade kit', 'cooling-power'],
        ['Replacement fan assembly', 'cooling-power'],
        ['Extended warranty coverage', 'hosting-services'],
        ['Recurring revenue computing', 'performance-roi'],
        ['Passive income stream generator', 'performance-roi'],
        ['Long term storage validation', 'network-validation'],
        ['Network security payment system', 'network-validation'],
        ['Algorithm independent miner', 'rigs-hardware'],
        ['Multi protocol validator', 'network-validation'],
        ['US based mining operation', 'facilities-builds'],
        ['European data center computing', 'facilities-builds'],
        ['Residential electricity rate comparison for miners', 'performance-roi'],
        ['Commercial grade units', 'rigs-hardware'],
        ['Industrial scale deployment', 'facilities-builds'],
        ['Warehouse conversion project', 'facilities-builds'],
        ['Off grid computing station', 'facilities-builds'],
        ['Computational proof system', 'network-validation'],
        ['Digital asset validator', 'network-validation'],
        ['Peer to peer network participant', 'network-validation'],
        ['Decentralized processing unit', 'network-validation'],
        ['Distributed storage verification service', 'network-validation'],
        ['Cryptographic computation device', 'network-validation'],
        ['Secure ledger maintainer', 'network-validation'],
        ['Automated consensus engine', 'network-validation'],
        ['Permanent record keeper', 'network-validation'],
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
 * uppercase ("GPU miner" stays "GPU miner", not "gPU miner").
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
    static $spokenAsWord = ['ASIC' => true];
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
        "How a %s fits into a modern mining setup",
        "Choosing a %s without overpaying",
        "Inside the %s: what actually moves the needle",
        "%s, explained for home and pro operators",
        "The state of the %s in 2026",
        "%s: the features that matter and the ones that don't",
        "What we learned running a %s around the clock",
    ];

    $leads = [
        "Every operation eventually argues about its %s, and for good reason — it sits on the critical path between the watts coming in and the useful work going out.",
        "Ask ten operators about the ideal %s and you will get eleven answers. Here is the framework we use to cut through the noise.",
        "The %s has quietly become table stakes, but most setups still get judged on the wrong criteria.",
        "A %s looks simple on a spec sheet and turns out to be anything but once it runs flat out, day and night.",
        "If you only upgrade one part of your setup this quarter, a properly chosen %s is a strong candidate.",
    ];

    $angles = [
        'rigs-hardware' => [
            'domain' => 'turning power into hashes',
            'why' => "When margins tighten and difficulty climbs, the gap between a good and a mediocre %s shows up directly in your hashrate per watt.",
            'bullets' => [
                'Hashrate and stability under sustained, round-the-clock load — not bench numbers',
                'Power draw at the wall and real efficiency in joules per unit of work',
                'Build quality of connectors, boards and solder under constant heat cycling',
                'Firmware maturity, tuning headroom and how often updates actually ship',
                'Spare-part availability and how quickly a dead unit comes back online',
            ],
        ],
        'cooling-power' => [
            'domain' => 'heat and electricity',
            'why' => "Heat and power are where most setups quietly bleed money; a weak %s turns expensive watts into noise and shortens hardware life.",
            'bullets' => [
                'Thermal headroom at your worst-case ambient, not a cool test lab',
                'Real power factor and draw under full load, measured at the wall',
                'Noise and airflow you can actually live with in the space you have',
                'Dust, humidity and corrosion tolerance over months of uptime',
                'Whether waste heat is simply dumped or recovered into something useful',
            ],
        ],
        'facilities-builds' => [
            'domain' => 'space, density and deployment',
            'why' => "Floor space, weight and airflow add up fast; the wrong %s caps how many units you can run long before your power does.",
            'bullets' => [
                'Rack density versus the airflow and service access you actually need',
                'Structural, fire and electrical code for the space you are converting',
                'How cleanly it scales from a handful of units to a full room',
                'Portability and lead time if the site or the power deal changes',
                'Total cost per slot once cooling, wiring and mounting are counted',
            ],
        ],
        'hosting-services' => [
            'domain' => 'managed uptime',
            'why' => "When someone else runs the hardware, a %s is only as good as its worst week — the SLA, the response time and what happens when a unit dies.",
            'bullets' => [
                'What the SLA actually guarantees on uptime, and the penalties if it slips',
                'Transparency on fees, power rates and the cut taken off the top',
                'Response time on dead units, repairs and RMA in practice',
                'Real monitoring and remote access, not a once-a-day status email',
                'Contract terms, lock-in and how cleanly you can walk away',
            ],
        ],
        'network-validation' => [
            'domain' => 'verifying work on the network',
            'why' => "On a public network a %s is judged by the protocol, not the brochure — a correct result counts and a wrong one is simply discarded.",
            'bullets' => [
                'Whether the implementation follows the protocol spec exactly',
                'How it behaves under high difficulty and contested conditions',
                'Latency from finished work to an accepted, confirmed result',
                'Resilience to reorgs, stale work and orphaned effort',
                'Whether rewards and shares are accounted for transparently',
            ],
        ],
        'performance-roi' => [
            'domain' => 'efficiency and payback',
            'why' => "A %s is the difference between a setup that pays for itself and one that just heats the room; the math is boring right up until it is the only thing that matters.",
            'bullets' => [
                'Whether it models electricity, heat and downtime — not just sticker hashrate',
                'Honest payback periods that assume difficulty rises over time',
                'How tuning and overclock settings trade efficiency against lifespan',
                'Realistic assumptions — no best-case-only numbers in the projection',
                'Alerts that flag a unit going unprofitable before the bill arrives',
            ],
        ],
        'getting-started' => [
            'domain' => 'a first working setup',
            'why' => "Starting out, a %s is where most beginners overspend or under-cool; getting it right early saves a painful, expensive rebuild later.",
            'bullets' => [
                'Whether your existing power and breakers can actually handle it',
                'Heat and noise in a shared, lived-in space — not a dedicated room',
                'A budget that counts power and cooling, not just the upfront box',
                'How easy it is to monitor, restart and maintain as a beginner',
                'A clear upgrade path so a small start does not become a dead end',
            ],
        ],
    ];

    $angle = $angles[$categorySlug];

    $definitions = [
        "At its core, a %s solves one job: %s. Everything else — the dashboards, the integrations, the marketing — hangs off that single responsibility.",
        "Strip away the branding and a %s is really a tool for %s. Judge it on how well it does that before anything else.",
        "Think of a %s as the layer that owns %s. When it works you forget it exists; when it fails, you feel it in your uptime and your power bill.",
    ];

    $closings = [
        "There is no universally \"best\" %s — only the one that matches your space, your power budget and the scale you actually run. Start from your constraints, not the spec sheet.",
        "Pick the %s you understand well enough to troubleshoot at 3 a.m. when a unit drops offline. Cleverness you cannot reason about is a liability, not an edge.",
        "The right %s fades into the background and lets you focus on uptime and efficiency. If you are fighting the gear, you have the wrong one.",
        "Run any %s at small scale first. The spec sheet never mentions the failure modes — your own logs and your power meter will.",
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
    <p>The usual trap is optimising for the happy path. A {$lower} that looks great on the bench can fall apart the moment heat, dust and 24/7 load build up — which is exactly when it matters most. Test it under sustained load, in real ambient conditions, and on the messiest power you actually have.</p>

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
        "A no-nonsense look at the %s — the numbers that matter and the traps that don't make the spec sheet.",
        "From first principles to the rack: the short version of everything you need to know about the %s.",
        "What a %s gets right, what it quietly gets wrong, and how to tell the difference in your own setup.",
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
