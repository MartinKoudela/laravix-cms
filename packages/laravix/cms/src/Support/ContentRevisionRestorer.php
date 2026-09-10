<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Laravix\Cms\Models\ContentRevision;

class ContentRevisionRestorer
{
    private const RESTORABLE_ATTRIBUTES = [
        'title',
        'slug',
        'status',
        'is_homepage',
        'published_at',
        'blocks',
        'grapesjs_data',
        'grapesjs_html',
    ];

    public function restore(ContentRevision $revision): void
    {
        $data = $revision->data ?? [];
        $content = $revision->content;

        $attributes = array_intersect_key($data, array_flip(self::RESTORABLE_ATTRIBUTES));

        if ($attributes !== []) {
            $content->update($attributes);
        }

        if (! array_key_exists('fields', $data) || ! is_array($data['fields'])) {
            return;
        }

        foreach ($data['fields'] as $key => $value) {
            $content->fields()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $content->fields()->whereNotIn('key', array_keys($data['fields']))->delete();
    }
}
