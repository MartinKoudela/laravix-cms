<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;

class TogglePublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'togglePublish';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (Content $record): string => $record->status === ContentStatus::PUBLISHED
            ? __('laravix::content.actions.unpublish')
            : __('laravix::content.actions.publish'));

        $this->icon(fn (Content $record) => $record->status === ContentStatus::PUBLISHED
            ? Heroicon::OutlinedEyeSlash
            : Heroicon::OutlinedRocketLaunch);

        $this->color('gray');

        $this->authorize(fn (Content $record): bool => auth()->user()?->can('update', $record) ?? false);

        $this->action(function (Content $record): void {
            $publishing = $record->status !== ContentStatus::PUBLISHED;

            $record->update([
                'status' => $publishing ? ContentStatus::PUBLISHED : ContentStatus::DRAFT,
                'published_at' => $publishing ? now() : null,
            ]);

            Notification::make()
                ->title($publishing
                    ? __('laravix::content.messages.published')
                    : __('laravix::content.messages.unpublished'))
                ->success()
                ->send();
        });
    }
}
