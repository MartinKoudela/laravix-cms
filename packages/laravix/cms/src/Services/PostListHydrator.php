<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Enums\ImageVariant;
use Laravix\Cms\Models\Content;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Collection;

class PostListHydrator
{
    public function hydrate(string $html, Collection $posts, Collection $mediaMap): string
    {
        if (! str_contains($html, 'data-lx-post-list')) {
            return $html;
        }

        $dom = $this->parse($html, 'lx-hydrate-root');
        $xpath = new DOMXPath($dom);
        $targets = $xpath->query('//*[@data-lx-post-list]');

        if ($targets === false || $targets->length === 0) {
            return $html;
        }

        $cardsHtml = $posts->map(fn (Content $post) => $this->renderCard($post, $mediaMap))->implode('');

        foreach ($targets as $target) {
            $this->replaceChildren($dom, $target, $cardsHtml);
        }

        $root = $xpath->query('//*[@id="lx-hydrate-root"]')->item(0);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        return $result;
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

    private function renderCard(Content $post, Collection $mediaMap): string
    {
        $ogImageId = (int) ($post->fields->firstWhere('key', 'og_image')?->value ?? 0);
        $media = $ogImageId ? $mediaMap->get($ogImageId) : null;
        $imageUrl = $media?->variantUrl(ImageVariant::MEDIUM);
        $href = $post->is_homepage ? '/' : '/'.$post->slug;
        $date = $post->published_at?->format('j. n. Y');

        $imageStyle = $imageUrl ? ' style="background-image:url('.e($imageUrl).')"' : '';

        return '<a href="'.e($href).'" class="lx-post-list__card">'
            .'<div class="lx-post-list__image"'.$imageStyle.'></div>'
            .'<div class="lx-post-list__body">'
            .'<h3 class="lx-post-list__title">'.e($post->title).'</h3>'
            .($date ? '<p class="lx-post-list__meta">'.e($date).'</p>' : '')
            .'</div>'
            .'</a>';
    }
}
