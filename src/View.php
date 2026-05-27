<?php

declare(strict_types=1);

namespace CryptoDesk;

/** Minimal PHP-template renderer: a view rendered into a shared layout. */
final class View
{
    /** @param array<string,mixed> $shared values exposed to every template (nav, site meta) */
    public function __construct(private string $dir, private array $shared = [])
    {
    }

    /** Render a single template file and return its output as a string. */
    public function render(string $template, array $data = []): string
    {
        $data = array_merge($this->shared, $data);
        extract($data, EXTR_SKIP);

        ob_start();
        require $this->dir . '/' . $template . '.php';

        return (string) ob_get_clean();
    }

    /**
     * Render a template, wrap it in the site layout, and echo the result.
     *
     * @param array<string,mixed> $meta per-page SEO/social metadata (description,
     *        image, type, canonical, published, author, section, noindex)
     */
    public function page(string $template, array $data, string $title, array $meta = []): void
    {
        $content = $this->render($template, $data);
        echo $this->render('layout', ['content' => $content, 'pageTitle' => $title, 'meta' => $meta]);
    }
}
