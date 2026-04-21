<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use App\Support\SettingComponentFactory;
use App\Support\SettingRegistry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ManageSettings extends Page
{
    protected static string $resource = SettingResource::class;

    protected string $view = 'filament.resources.settings.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $siteId = filament()->getTenant()?->id;

        $saved = Setting::where('site_id', $siteId)
            ->pluck('value', 'key')
            ->toArray();

        $defaults = collect(SettingRegistry::all())
            ->mapWithKeys(fn ($definition) => [$definition->key => $definition->default])
            ->toArray();

        $this->form->fill(array_merge($defaults, $saved));
    }

    public function form(Schema $schema): Schema
    {
        $tabs = [];

        foreach (SettingRegistry::grouped() as $group => $definitions) {
            $components = array_map(
                fn ($definition) => SettingComponentFactory::make($definition),
                $definitions,
            );

            $tabs[] = Tab::make($group)->schema([
                Section::make()->schema($components)->columns(2),
            ]);
        }

        return $schema->statePath('data')->components([
            Tabs::make()->tabs($tabs),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $siteId = filament()->getTenant()?->id;

        foreach ($state as $key => $value) {
            Setting::updateOrCreate(
                ['site_id' => $siteId, 'key' => $key],
                ['value' => $value],
            );
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }
}
