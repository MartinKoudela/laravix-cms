<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Js;
use Laravix\Cms\Models\Media;

class CopyMediaUrlAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'copyUrl';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::media.actions.copy_url'));
        $this->icon(Heroicon::OutlinedClipboardDocument);
        $this->color('gray');

        $this->alpineClickHandler(function (Media $record): string {
            $url = Js::from($record->url);
            $message = Js::from(__('laravix::media.actions.url_copied'));

            return <<<JS
                window.navigator.clipboard.writeText({$url})
                \$tooltip({$message}, { theme: \$store.theme, timeout: 2000 })
                JS;
        });
    }
}
