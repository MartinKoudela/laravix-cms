<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Support\ContentTypeDefinition;
use Laravix\Cms\Support\ContentTypeRegistry;

/**
 * Jumps to the content list pre-filtered by this taxonomy. The list is always scoped to one
 * content type, so the type most of the taxonomy's contents use wins; an empty taxonomy falls
 * back to the first type that accepts its kind.
 */
class ShowTaxonomyContentsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'showContents';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::taxonomy.actions.show_contents'));
        $this->icon(Heroicon::OutlinedDocumentText);
        $this->color('gray');

        $this->visible(fn (Taxonomy $record): bool => static::contentTypeFor($record) !== null);

        $this->url(fn (Taxonomy $record): string => ContentResource::getUrl('index', [
            'type' => static::contentTypeFor($record)?->key,
            'taxonomy' => $record->id,
        ]));
    }

    public static function contentTypeFor(Taxonomy $record): ?ContentTypeDefinition
    {
        $mostUsed = $record->contents()
            ->select('type')
            ->groupBy('type')
            ->orderByRaw('count(*) desc')
            ->value('type');

        if ($mostUsed !== null && ContentTypeRegistry::has($mostUsed)) {
            return ContentTypeRegistry::find($mostUsed);
        }

        foreach (ContentTypeRegistry::all() as $type) {
            if ($type->taxonomyTypes === [] || in_array($record->type, $type->taxonomyTypes, true)) {
                return $type;
            }
        }

        return null;
    }
}
