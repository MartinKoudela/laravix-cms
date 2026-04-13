<?php

namespace App\Filament\Resources\Contents\Schemas;

use App\Enums\ContentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (TextInput $component, ?string $state) =>
                                $component->getContainer()->getComponent('slug')
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
                        Select::make('site_id')
                            ->relationship('site', 'name')
                            ->required()
                            ->searchable(),
                    ]),
                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->required()
                            ->options([
                                'page' => 'Page',
                                'post' => 'Post',
                            ])
                            ->default('page'),
                        Select::make('status')
                            ->required()
                            ->options(collect(ContentStatus::cases())->mapWithKeys(
                                fn (ContentStatus $case) => [$case->value => $case->name]
                            ))
                            ->default(ContentStatus::Draft->value)
                            ->live(),
                        DateTimePicker::make('published_at')
                            ->visible(fn (Get $get): bool => $get('status') === ContentStatus::Scheduled->value),
                    ]),
            ]);
    }
}
