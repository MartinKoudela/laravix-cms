<?php

namespace App\Support;

class FastActions
{
    public static function all(?string $tenantId = null): array
    {
        return [
            'new_content' => [
                'label' => __('fast_actions.new_content'),
                'icon' => 'heroicon-o-document-plus',
                'url' => route('filament.admin.resources.contents.create', $tenantId),
            ],
            'new_media' => [
                'label' => __('fast_actions.new_media'),
                'icon' => 'heroicon-o-photo',
                'url' => route('filament.admin.resources.media.create', $tenantId),
            ],
            'new_taxonomy' => [
                'label' => __('fast_actions.new_taxonomy'),
                'icon' => 'heroicon-o-tag',
                'url' => route('filament.admin.resources.taxonomies.create', $tenantId),
            ],
            'new_user' => [
                'label' => __('fast_actions.new_user'),
                'icon' => 'heroicon-o-users',
                'url' => route('filament.admin.resources.users.create', $tenantId),
            ],
            'account_settings' => [
                'label' => __('fast_actions.user_settings'),
                'icon' => 'heroicon-o-cog-6-tooth',
                'url' => route('filament.admin.auth.profile'),
            ],
        ];
    }
}
