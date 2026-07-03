<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use App\Models\Site;
use App\Support\SettingComponentFactory;
use App\Support\SettingRegistry;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Arr;
use Livewire\Attributes\Url;

class ManageSettings extends Page
{
    protected static string $resource = SettingResource::class;

    protected string $view = 'filament.resources.settings.pages.manage-settings';

    #[Url]
    public string $group = '';

    public function getTitle(): string
    {
        return __(SettingResource::groupOptions()[$this->group]);
    }

    public ?array $data = [];

    public function mount(): void
    {
        $groups = SettingResource::groupOptions();

        if (! array_key_exists($this->group, $groups)) {
            $this->group = array_key_first($groups);
        }

        $siteId = filament()->getTenant()?->id;

        $saved = Setting::where('site_id', $siteId)
            ->pluck('value', 'key')
            ->toArray();

        $defaults = collect(SettingRegistry::all())
            ->mapWithKeys(fn ($definition) => [$definition->key => $definition->default])
            ->toArray();

        $this->form->fill(array_merge($defaults, $saved, [
            'theme' => filament()->getTenant()?->theme,
        ]));
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make()->schema($this->activeGroupComponents())->columns(2),
        ]);
    }


    private function activeGroupComponents(): array
    {
        if ($this->group === 'appearance') {
            return [
                Select::make('theme')
                    ->label(__('common.theme'))
                    ->allowHtml()
                    ->options(collect(Site::availableThemes())
                        ->mapWithKeys(fn ($label, $key) => [$key => Site::themeOptionLabel($key)])
                        ->all()
                    )
                    ->default('default')
                    ->required()
                    ->helperText(__('settings.hints.theme')),
            ];
        }

        if ($this->group === 'api') {
            return [
                TextInput::make('api_base_url')
                    ->label(__('settings.fields.api_base_url'))
                    ->afterStateHydrated(fn (TextInput $component) => $component->state('https://'.filament()->getTenant()->domain.'/api/v1'))
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText(__('settings.hints.api_base_url'))
                    ->suffixAction(
                        Action::make('copy')
                            ->icon(Heroicon::OutlinedClipboardDocument)
                            ->alpineClickHandler('window.navigator.clipboard.writeText($el.closest(\'.fi-input-wrp\').querySelector(\'input\').value); $el.style.color = \'green\'; setTimeout(() => $el.style.color = \'\', 1500)')
                    ),
            ];
        }

        $groupKey = SettingResource::groupOptions()[$this->group];
        $definitions = SettingRegistry::grouped()[$groupKey] ?? [];

        return array_map(
            fn ($definition) => SettingComponentFactory::make($definition),
            $definitions,
        );
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $siteId = filament()->getTenant()?->id;

        $theme = Arr::pull($state, 'theme');

        foreach ($state as $key => $value) {
            Setting::updateOrCreate(
                ['site_id' => $siteId, 'key' => $key],
                ['value' => $value],
            );
        }

        if ($theme !== null) {
            filament()->getTenant()->update(['theme' => $theme]);
        }

        Notification::make()
            ->title(__('settings.messages.saved'))
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('settings.actions.save'))
                ->action('save'),
        ];
    }
}
