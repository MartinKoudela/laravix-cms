<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Settings\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Laravix\Cms\Filament\Resources\Settings\SettingResource;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\SiteApiToken;
use Laravix\Cms\Support\SettingComponentFactory;
use Laravix\Cms\Support\SettingRegistry;
use Livewire\Attributes\Url;

class ManageSettings extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = SettingResource::class;

    protected string $view = 'laravix::filament.resources.settings.pages.manage-settings';

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
                    ->label(__('laravix::common.theme'))
                    ->allowHtml()
                    ->options(collect(Site::availableThemes())
                        ->mapWithKeys(fn ($label, $key) => [$key => Site::themeOptionLabel($key)])
                        ->all()
                    )
                    ->default('default')
                    ->required()
                    ->helperText(__('laravix::settings.hints.theme')),
            ];
        }

        if ($this->group === 'api') {
            return [
                TextInput::make('api_base_url')
                    ->label(__('laravix::settings.fields.api_base_url'))
                    ->afterStateHydrated(fn (TextInput $component) => $component->state('https://'.filament()->getTenant()->domain.'/api/v1'))
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText(__('laravix::settings.hints.api_base_url'))
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
            ->title(__('laravix::settings.messages.saved'))
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(SiteApiToken::query()->where('site_id', filament()->getTenant()?->id))
            ->columns([
                TextColumn::make('name')
                    ->label(__('laravix::settings.api_tokens.fields.name'))
                    ->searchable(),
                TextColumn::make('prefix')
                    ->label(__('laravix::settings.api_tokens.fields.token'))
                    ->formatStateUsing(fn (string $state): string => $state.'…')
                    ->fontFamily(FontFamily::Mono),
                TextColumn::make('last_used_at')
                    ->label(__('laravix::settings.api_tokens.fields.last_used'))
                    ->since()
                    ->placeholder(__('laravix::settings.api_tokens.messages.never_used')),
                TextColumn::make('expires_at')
                    ->label(__('laravix::settings.api_tokens.fields.expires'))
                    ->dateTime()
                    ->placeholder(__('laravix::settings.api_tokens.messages.never_expires'))
                    ->color(fn ($state): string => $state?->isPast() ? 'danger' : 'gray'),
                TextColumn::make('created_at')
                    ->label(__('laravix::settings.api_tokens.fields.created'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->label(__('laravix::settings.api_tokens.actions.revoke'))
                    ->modalHeading(__('laravix::settings.api_tokens.actions.revoke')),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('laravix::settings.api_tokens.messages.empty'));
    }

    public function revealTokenAction(): Action
    {
        return Action::make('revealToken')
            ->modalHeading(__('laravix::settings.api_tokens.messages.reveal_heading'))
            ->schema([
                TextInput::make('plaintext')
                    ->label(__('laravix::settings.api_tokens.fields.token'))
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText(__('laravix::settings.api_tokens.messages.reveal_hint'))
                    ->suffixAction(
                        Action::make('copy')
                            ->icon(Heroicon::OutlinedClipboardDocument)
                            ->alpineClickHandler('window.navigator.clipboard.writeText($el.closest(\'.fi-input-wrp\').querySelector(\'input\').value); $el.style.color = \'green\'; setTimeout(() => $el.style.color = \'\', 1500)')
                    ),
            ])
            ->fillForm(fn (array $arguments): array => ['plaintext' => $arguments['plaintext']])
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('laravix::settings.api_tokens.actions.done'))
            ->closeModalByClickingAway(false);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createToken')
                ->label(__('laravix::settings.api_tokens.actions.create'))
                ->icon(Heroicon::OutlinedPlus)
                ->visible(fn (): bool => $this->group === 'api')
                ->schema([
                    TextInput::make('name')
                        ->label(__('laravix::settings.api_tokens.fields.name'))
                        ->required(),
                    DateTimePicker::make('expires_at')
                        ->label(__('laravix::settings.api_tokens.fields.expires'))
                        ->nullable(),
                ])
                ->action(function (array $data): void {
                    $result = SiteApiToken::generateFor(
                        filament()->getTenant(),
                        $data['name'],
                        $data['expires_at'] ? Carbon::parse($data['expires_at']) : null,
                    );

                    $this->replaceMountedAction('revealToken', ['plaintext' => $result['plaintext']]);
                }),
            Action::make('save')
                ->label(__('laravix::settings.actions.save'))
                ->visible(fn (): bool => $this->group !== 'api')
                ->action('save'),
        ];
    }
}
