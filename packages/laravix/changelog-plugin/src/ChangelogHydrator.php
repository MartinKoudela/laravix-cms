<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Laravix\Changelog\Models\ChangelogRelease;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\BlockHydrator;

class ChangelogHydrator implements BlockHydrator
{
    public function hydrate(string $html, Site $site, Content $content): string
    {
        if (! str_contains($html, 'data-lx-changelog')) {
            return $html;
        }

        $dom = $this->parse($html, 'lx-hydrate-root');
        $xpath = new DOMXPath($dom);
        $targets = $xpath->query('//*[@data-lx-changelog]');

        if ($targets === false || $targets->length === 0) {
            return $html;
        }

        $releases = ChangelogRelease::query()
            ->where('site_id', $site->id)
            ->with('items')
            ->orderByDesc('released_at')
            ->get();

        $releasesHtml = $this->styles().$releases->map(fn (ChangelogRelease $release) => $this->renderRelease($release))->implode('');

        foreach ($targets as $target) {
            $this->replaceChildren($dom, $target, $releasesHtml);
        }

        $root = $xpath->query('//*[@id="lx-hydrate-root"]')->item(0);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return $result;
    }

    private function renderRelease(ChangelogRelease $release): string
    {
        $items = $release->items->map(fn ($item) => '<li>'
            .'<span class="lx-changelog__type lx-changelog__type--'.e($item->type).'">'
            .e(__('changelog::changelog.types.'.$item->type))
            .'</span> '
            .e($item->localizedText())
            .'</li>'
        )->implode('');

        return '<div class="lx-changelog__release">'
            .'<div class="lx-changelog__header">'
            .'<span class="lx-changelog__version">v'.e($release->version).'</span>'
            .'<span class="lx-changelog__date">'.e($release->released_at->format('j. n. Y')).'</span>'
            .'</div>'
            .($release->localizedTitle() ? '<p class="lx-changelog__title">'.e($release->localizedTitle()).'</p>' : '')
            .'<ul class="lx-changelog__items">'.$items.'</ul>'
            .'</div>';
    }

    private function styles(): string
    {
        return <<<'HTML'
<style>
.lx-changelog__release { margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(0,0,0,.08); }
.lx-changelog__release:last-child { border-bottom: none; }
.lx-changelog__header { display: flex; align-items: baseline; gap: 12px; margin-bottom: .5rem; }
.lx-changelog__version { font-size: 1.15rem; font-weight: 700; }
.lx-changelog__date { opacity: .6; font-size: .875rem; }
.lx-changelog__title { margin-bottom: .5rem; opacity: .8; }
.lx-changelog__items { list-style: none; padding: 0; }
.lx-changelog__items li { padding: 3px 0; }
.lx-changelog__type { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: 2px 8px; border-radius: 9999px; margin-right: 6px; }
.lx-changelog__type--added { background: #dcfce7; color: #166534; }
.lx-changelog__type--changed { background: #dbeafe; color: #1e40af; }
.lx-changelog__type--fixed { background: #fef9c3; color: #854d0e; }
.lx-changelog__type--removed { background: #fee2e2; color: #991b1b; }
.lx-changelog__type--security { background: #f3e8ff; color: #6b21a8; }
</style>
HTML;
    }

    private function replaceChildren(DOMDocument $dom, DOMElement $target, string $innerHtml): void
    {
        while ($target->firstChild) {
            $target->removeChild($target->firstChild);
        }

        $fragment = $this->parse($innerHtml, 'lx-fragment-root');
        $fragmentRoot = (new DOMXPath($fragment))->query('//*[@id="lx-fragment-root"]')->item(0);

        foreach ($fragmentRoot->childNodes as $child) {
            $target->appendChild($dom->importNode($child, true));
        }
    }

    private function parse(string $html, string $wrapperId): DOMDocument
    {
        $dom = new DOMDocument;

        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8"?><div id="'.$wrapperId.'">'.$html.'</div>');
        libxml_use_internal_errors($previous);

        return $dom;
    }
}
