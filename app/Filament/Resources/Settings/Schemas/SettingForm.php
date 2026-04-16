<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'settings', column: 'key', ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('site_id', filament()->getTenant()?->id))
                            ->helperText('Unique key, e.g. "site_name", "google_analytics".'),
                        Textarea::make('value')
                            ->helperText('The value for this setting.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
