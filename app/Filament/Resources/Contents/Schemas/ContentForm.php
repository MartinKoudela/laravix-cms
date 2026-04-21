<?php

namespace App\Filament\Resources\Contents\Schemas;

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\Taxonomy;
use App\Support\AppearanceComponentFactory;
use App\Support\AppearanceRegistry;
use App\Support\FieldComponentFactory;
use App\Support\FieldRegistry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        $grouped = FieldRegistry::grouped();
        $seoDefinitions = $grouped['SEO'] ?? [];
        $contentGroups = array_filter($grouped, fn (string $key) => $key !== 'SEO', ARRAY_FILTER_USE_KEY);
        $appearanceDefinitions = AppearanceRegistry::forContentType(null);

        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Content')
                            ->schema([
                                Section::make('General')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(fn (TextInput $component, ?string $state) => $component->getContainer()->getComponent('slug')
                                                ?->state(str($state ?? '')->slug()->toString())
                                            )
                                            ->columnSpanFull(),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->key('slug')
                                            ->prefix('/')
                                            ->unique(table: 'contents', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule, callable $get) => $rule->where('site_id', $get('site_id')))
                                            ->helperText('Must be unique per site.'),
                                        Toggle::make('is_homepage')
                                            ->label('Set as homepage')
                                            ->helperText('Only one content per site can be the homepage.')
                                            ->columnSpanFull()
                                            ->hidden(function (?Content $record): bool {
                                                if ($record?->is_homepage) {
                                                    return false;
                                                }

                                                $siteId = filament()->getTenant()?->id;

                                                if (! $siteId) {
                                                    return false;
                                                }

                                                return Content::where('site_id', $siteId)
                                                    ->where('is_homepage', true)
                                                    ->exists();
                                            }),
                                    ]),
                                Section::make('Publishing')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->required()
                                            ->options([
                                                'page' => 'Page',
                                                'post' => 'Post',
                                                'archive' => 'Archive',
                                            ])
                                            ->default('page')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->dehydrated(),
                                        Select::make('status')
                                            ->required()
                                            ->options(collect(ContentStatus::cases())->mapWithKeys(
                                                fn (ContentStatus $case) => [$case->value => $case->name]
                                            ))
                                            ->default(ContentStatus::DRAFT->value)
                                            ->live(),
                                        DateTimePicker::make('published_at')
                                            ->visible(fn (Get $get): bool => $get('status') === ContentStatus::SCHEDULED->value),
                                    ]),
                                Section::make('Taxonomies')
                                    ->schema([
                                        Select::make('taxonomies')
                                            ->relationship('taxonomies', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->options(fn () => Taxonomy::where('site_id', filament()->getTenant()?->id)
                                                ->pluck('name', 'id')
                                            ),
                                    ]),
                                ...array_map(
                                    fn (string $group, array $definitions) => Section::make($group)
                                        ->schema(array_map(
                                            fn ($definition) => FieldComponentFactory::make($definition),
                                            $definitions,
                                        ))
                                        ->columns(2),
                                    array_keys($contentGroups),
                                    array_values($contentGroups),
                                ),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema(array_map(
                                        fn ($definition) => FieldComponentFactory::make($definition),
                                        $seoDefinitions,
                                    )),
                            ]),
                        Tab::make('Appearance')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema(array_map(
                                        fn ($definition) => AppearanceComponentFactory::make($definition),
                                        $appearanceDefinitions,
                                    )),
                            ]),
                    ]),
            ]);
    }
}
