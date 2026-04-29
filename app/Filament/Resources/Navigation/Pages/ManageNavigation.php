<?php

namespace App\Filament\Resources\Navigation\Pages;

use App\Filament\Resources\Navigation\NavigationResource;
use App\Models\Site;
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

    public ?array $headerData = [];

    public ?array $footerData = [];

    public ?Site $site = null;

    public string $previewToken = '';

    public function mount(): void
    {
        $this->site = filament()->getTenant();
        $navigations = $this->site?->navigations ?? [];

        $this->headerForm->fill(['navigations' => ['header' => $navigations['header'] ?? []]]);
        $this->footerForm->fill(['navigations' => ['footer' => $navigations['footer'] ?? []]]);

        $this->refreshPreview();
    }

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'headerData') || str_starts_with($property, 'footerData')) {
            $this->refreshPreview();
        }
    }

    public function refreshPreview(): void
    {
        $this->previewToken = md5($this->site->id.'-'.auth()->id().'-nav-preview');

        cache()->put("preview_nav_{$this->previewToken}", [
            'site_id'     => $this->site->id,
            'navigations' => array_merge(
                $this->headerData['navigations'] ?? [],
                $this->footerData['navigations'] ?? [],
            ),
        ], now()->addMinutes(30));

        $this->dispatch('nav-preview-updated');
    }

    public function headerForm(Schema $schema): Schema
    {
        $definition = NavigationRegistry::all()['header'] ?? null;

        return $schema
            ->statePath('headerData')
            ->components($definition ? [NavigationComponentFactory::make($definition)] : []);
    }

    public function footerForm(Schema $schema): Schema
    {
        $definition = NavigationRegistry::all()['footer'] ?? null;

        return $schema
            ->statePath('footerData')
            ->components($definition ? [NavigationComponentFactory::make($definition)] : []);
    }

    protected function getForms(): array
    {
        return ['headerForm', 'footerForm'];
    }

    public function save(): void
    {
        $site = $this->site ?? filament()->getTenant();

        $site->update([
            'navigations' => array_merge(
                $this->headerForm->getState()['navigations'] ?? [],
                $this->footerForm->getState()['navigations'] ?? [],
            ),
        ]);

        $this->refreshPreview();

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