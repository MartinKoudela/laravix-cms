<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs;

use Illuminate\Support\Collection;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownRenderer
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $environment = new Environment([
            'heading_permalink' => [
                'html_class' => 'heading-anchor',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'symbol' => '#',
                'insert' => 'after',
                'apply_id_to_heading' => true,
            ],
            'external_link' => [
                'internal_hosts' => parse_url((string) config('app.url'), PHP_URL_HOST),
                'open_in_new_window' => true,
                'nofollow' => 'external',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AutolinkExtension);
        $environment->addExtension(new ExternalLinkExtension);
        $environment->addExtension(new HeadingPermalinkExtension);

        $this->converter = new MarkdownConverter($environment);
    }

    public function toHtml(string $markdown): string
    {
        return (string) $this->converter->convert($markdown);
    }

    public function toc(string $html): Collection
    {
        preg_match_all('/<h([23])[^>]*\sid="([^"]+)"[^>]*>(.*?)<\/h[23]>/s', $html, $matches, PREG_SET_ORDER);

        return collect($matches)->map(fn (array $match) => [
            'level' => (int) $match[1],
            'id' => $match[2],
            'text' => trim(strip_tags(preg_replace('/<a[^>]*class="heading-anchor"[^>]*>.*?<\/a>/s', '', $match[3]))),
        ]);
    }
}
