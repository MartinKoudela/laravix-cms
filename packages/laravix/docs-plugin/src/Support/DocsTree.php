<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs\Support;

use Illuminate\Support\Collection;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;

class DocsTree
{
    private Collection $taxonomies;

    public function __construct(
        private readonly Site $site,
        private readonly string $locale,
    ) {
        $this->taxonomies = Taxonomy::query()
            ->where('site_id', $site->id)
            ->where('type', 'doc-category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->keyBy('id');
    }

    public function sections(): Collection
    {
        return $this->taxonomies
            ->filter(fn (Taxonomy $taxonomy) => $taxonomy->parent_id === null)
            ->values();
    }

    public function resolveSection(string $slug): ?Taxonomy
    {
        return $this->sections()->first(
            fn (Taxonomy $taxonomy) => $taxonomy->slug === $slug
                || $taxonomy->localizedSlug($this->locale) === $slug,
        );
    }

    public function sectionFor(Content $doc): ?Taxonomy
    {
        $category = $this->categoryFor($doc);

        if (! $category) {
            return null;
        }

        $taxonomy = $this->taxonomies->get($category->id);

        while ($taxonomy && $taxonomy->parent_id !== null) {
            $taxonomy = $this->taxonomies->get($taxonomy->parent_id);
        }

        return $taxonomy;
    }

    public function subtreeIds(Taxonomy $section): Collection
    {
        return $this->subtree($section)->pluck('id');
    }

    public function pathFor(Content $doc, string $defaultLocale): string
    {
        $prefix = $this->locale === $defaultLocale ? '/docs' : '/'.$this->locale.'/docs';

        $section = $this->sectionFor($doc);

        return $section
            ? $prefix.'/'.$section->localizedSlug($this->locale).'/'.$doc->slug
            : $prefix.'/'.$doc->slug;
    }

    public function sectionPath(Taxonomy $section, string $defaultLocale): string
    {
        $prefix = $this->locale === $defaultLocale ? '/docs' : '/'.$this->locale.'/docs';

        return $prefix.'/'.$section->localizedSlug($this->locale);
    }

    public function groupedForSection(Taxonomy $section, Collection $docs): Collection
    {
        $byCategory = $docs->groupBy(
            fn (Content $doc) => $this->categoryFor($doc)?->id ?? 0,
        );

        return $this->subtree($section)
            ->map(fn (Taxonomy $taxonomy) => [
                'label' => $taxonomy->id === $section->id ? null : $taxonomy->localizedName($this->locale),
                'docs' => ($byCategory->get($taxonomy->id) ?? collect())
                    ->sortBy([['sort_order', 'asc'], ['title', 'asc']])
                    ->values(),
            ])
            ->filter(fn (array $group) => $group['docs']->isNotEmpty())
            ->values();
    }

    private function categoryFor(Content $doc): ?Taxonomy
    {
        return $doc->taxonomies
            ->filter(fn (Taxonomy $taxonomy) => $taxonomy->type === 'doc-category')
            ->sortByDesc(fn (Taxonomy $taxonomy) => $this->depth($taxonomy))
            ->first();
    }

    private function depth(Taxonomy $taxonomy): int
    {
        $depth = 0;

        while ($taxonomy->parent_id !== null && $taxonomy = $this->taxonomies->get($taxonomy->parent_id)) {
            $depth++;
        }

        return $depth;
    }

    private function subtree(Taxonomy $section): Collection
    {
        $result = collect([$section]);
        $queue = [$section->id];

        while ($queue) {
            $parentId = array_shift($queue);

            foreach ($this->taxonomies as $taxonomy) {
                if ($taxonomy->parent_id === $parentId) {
                    $result->push($taxonomy);
                    $queue[] = $taxonomy->id;
                }
            }
        }

        return $result->values();
    }
}
