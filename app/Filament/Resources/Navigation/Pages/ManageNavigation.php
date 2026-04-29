<?php

namespace App\Filament\Resources\Navigation\Pages;

use App\Filament\Resources\Navigation\NavigationResource;
use App\Support\NavigationComponentFactory;
use App\Support\NavigationRegistry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class ManageNavigation extends Page
{
    protected static string $resource = NavigationResource::class;

    protected string $view = 'filament.resources.navigation.pages.manage-navigation';

    public function getTitle(): string
    {
        return __('Manage Navigation');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $site = filament()->getTenant();

        $this->form->fill([
            'navigations' => $site?->navigations ?? [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $components = array_map(
            fn ($definition) => NavigationComponentFactory::make($definition),
            NavigationRegistry::all(),
        );

        return $schema->statePath('data')->components($components);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $site = filament()->getTenant();

        $site->update([
            'navigations' => $state['navigations'] ?? [],
        ]);

        Notification::make()
            ->title(__('Navigation saved'))
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Navigation'))
                ->action('save'),
        ];
    }
}
