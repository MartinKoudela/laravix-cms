<?php

namespace App\Filament\Pages\Tenancy;

use App\Enums\SiteRole;
use App\Models\Site;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterSite extends RegisterTenant
{
    public function hasLogo(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function getLabel(): string
    {
        return 'Create Site';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The public name of the website.'),
                TextInput::make('domain')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('example.com')
                    ->unique(table: 'sites', column: 'domain'),
                Select::make('theme')
                    ->required()
                    ->default('default')
                    ->options(Site::availableThemes()),
            ]);
    }

    protected function handleRegistration(array $data): Site
    {
        $site = Site::create($data);

        $site->users()->attach(auth()->id());

        return $site;
    }
}
