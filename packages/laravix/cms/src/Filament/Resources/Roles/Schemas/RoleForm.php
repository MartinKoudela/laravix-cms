<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Role;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\PermissionDefinition;
use Laravix\Cms\Support\PermissionRegistry;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('laravix::common.general'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('laravix::common.name'))
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn (?Role $record): bool => $record?->is_system ?? false)
                            ->dehydrated(fn (?Role $record): bool => ! ($record?->is_system ?? false)),
                        Select::make('preset')
                            ->label(__('laravix::roles.fields.preset'))
                            ->helperText(__('laravix::roles.messages.preset_hint'))
                            ->options(fn (): array => static::presetOptions())
                            ->dehydrated(false)
                            ->live()
                            ->visible(fn (?Role $record): bool => $record === null)
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if ($state === null) {
                                    return;
                                }

                                $tenant = filament()->getTenant();
                                $preset = $tenant instanceof Site ? Role::findBySlug($tenant, $state) : null;
                                $permissions = $preset?->permissions ?? [];

                                foreach (static::grantableGroups() as $group => $definitions) {
                                    $set("grants.{$group}", array_values(array_filter(
                                        array_map(fn (PermissionDefinition $definition): string => $definition->key, $definitions),
                                        fn (string $key): bool => in_array($key, $permissions, true),
                                    )));
                                }
                            }),
                    ]),
                Section::make(__('laravix::roles.sections.permissions'))
                    ->columnSpanFull()
                    ->columns(4)
                    ->description(__('laravix::roles.messages.permissions_hint'))
                    ->schema(static::permissionLists()),
            ]);
    }

    protected static function permissionLists(): array
    {
        $lists = [];

        foreach (static::grantableGroups() as $group => $definitions) {
            $lists[] = CheckboxList::make("grants.{$group}")
                ->label(static::groupLabel($group))
                ->options(collect($definitions)->mapWithKeys(
                    fn (PermissionDefinition $definition): array => [$definition->key => static::permissionLabel($definition)]
                )->all())
                ->bulkToggleable();
        }

        return $lists;
    }

    public static function grantableGroups(): array
    {
        $user = auth()->user();
        $tenant = filament()->getTenant();
        $site = $tenant instanceof Site ? $tenant : null;

        $groups = [];

        foreach (PermissionRegistry::grouped() as $group => $definitions) {
            $allowed = array_values(array_filter(
                $definitions,
                fn (PermissionDefinition $definition): bool => $user instanceof User && $user->hasSitePermission($site, $definition->key),
            ));

            if ($allowed !== []) {
                $groups[$group] = $allowed;
            }
        }

        return $groups;
    }

    protected static function presetOptions(): array
    {
        return collect([SiteRole::EDITOR, SiteRole::VIEWER])
            ->mapWithKeys(fn (SiteRole $role): array => [$role->value => $role->label()])
            ->all();
    }

    public static function groupLabel(string $group): string
    {
        $key = 'laravix::permissions.groups.'.$group;

        return Lang::has($key) ? __($key) : Str::headline($group);
    }

    public static function permissionLabel(PermissionDefinition $definition): string
    {
        if (Lang::has($definition->label)) {
            return __($definition->label);
        }

        return $definition->label === $definition->key
            ? Str::headline(Str::afterLast($definition->key, '.'))
            : $definition->label;
    }
}
