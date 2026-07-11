<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Navigation\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravix\Cms\Filament\Resources\Navigation\NavigationResource;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\NavigationComponentFactory;
use Laravix\Cms\Support\NavigationRegistry;

class ManageNavigation extends Page
{
    protected static string $resource = NavigationResource::class;

    protected string $view = 'laravix::filament.resources.navigation.pages.manage-navigation';

    public function getTitle(): string
    {
        return __('laravix::navigation.actions.manage');
    }

    public ?array $headerData = [];

    public ?array $footerData = [];

    public ?array $headerDesignData = [];

    public ?array $footerDesignData = [];

    public ?Site $site = null;

    public string $previewToken = '';

    public function mount(): void
    {
        $this->site = filament()->getTenant();
        $navigations = $this->site?->navigations ?? [];
        $navDesign = $this->site?->nav_design ?? [];

        $this->headerForm->fill(['navigations' => ['header' => $navigations['header'] ?? []]]);
        $this->footerForm->fill(['navigations' => ['footer' => $navigations['footer'] ?? []]]);
        $this->headerDesignForm->fill(['nav_design' => ['header' => $navDesign['header'] ?? []]]);
        $this->footerDesignForm->fill(['nav_design' => ['footer' => $navDesign['footer'] ?? []]]);

        $this->refreshPreview();
    }

    public function updated(string $property): void
    {
        if (
            str_starts_with($property, 'headerData')
            || str_starts_with($property, 'footerData')
            || str_starts_with($property, 'headerDesignData')
            || str_starts_with($property, 'footerDesignData')
        ) {
            $this->refreshPreview();
        }
    }

    public function refreshPreview(): void
    {
        $this->previewToken = md5($this->site->id.'-'.auth()->id().'-nav-preview');

        cache()->put("preview_nav_{$this->previewToken}", [
            'site_id' => $this->site->id,
            'navigations' => array_merge(
                $this->headerData['navigations'] ?? [],
                $this->footerData['navigations'] ?? [],
            ),
            'nav_design' => array_merge(
                $this->headerDesignData['nav_design'] ?? [],
                $this->footerDesignData['nav_design'] ?? [],
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

    public function headerDesignForm(Schema $schema): Schema
    {
        [$colorInput, $fontFamilyOptions, $fontWeightOptions, $borderWidthOptions, $shadowOptions, $linksAlignOptions,, $iconPositionOptions] = $this->designFormSharedOptions();

        return $schema
            ->statePath('headerDesignData')
            ->components([
                Section::make(__('laravix::navigation.labels.header_design'))
                    ->schema([

                        Section::make(__('laravix::navigation.design.section_colors'))
                            ->schema([
                                $colorInput('nav_design.header.bg_color', __('laravix::navigation.design.bg_color')),
                                $colorInput('nav_design.header.text_color', __('laravix::navigation.design.text_color')),
                                $colorInput('nav_design.header.hover_color', __('laravix::navigation.design.hover_color')),
                                $colorInput('nav_design.header.active_color', __('laravix::navigation.design.active_color')),
                            ])
                            ->columns(4),

                        Section::make(__('laravix::navigation.design.section_border'))
                            ->schema([
                                $colorInput('nav_design.header.border_color', __('laravix::navigation.design.border_color')),
                                Select::make('nav_design.header.border_width')
                                    ->label(__('laravix::navigation.design.border_width'))
                                    ->options($borderWidthOptions)
                                    ->live(),
                                Select::make('nav_design.header.shadow')
                                    ->label(__('laravix::navigation.design.shadow'))
                                    ->options($shadowOptions)
                                    ->live(),
                            ])
                            ->columns(3),

                        Section::make(__('laravix::navigation.design.section_typography'))
                            ->schema([
                                Select::make('nav_design.header.font_family')
                                    ->label(__('laravix::navigation.design.font_family'))
                                    ->options($fontFamilyOptions)
                                    ->columnSpan(2)
                                    ->live(),
                                TextInput::make('nav_design.header.font_size')
                                    ->label(__('laravix::navigation.design.font_size'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->live(debounce: 500),
                                Select::make('nav_design.header.font_weight')
                                    ->label(__('laravix::navigation.design.font_weight'))
                                    ->options($fontWeightOptions)
                                    ->live(),
                            ])
                            ->columns(4),

                        Section::make(__('laravix::navigation.design.section_layout'))
                            ->schema([
                                TextInput::make('nav_design.header.height')
                                    ->label(__('laravix::navigation.design.height'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->placeholder('64')
                                    ->live(debounce: 500),
                                TextInput::make('nav_design.header.logo_height')
                                    ->label(__('laravix::navigation.design.logo_height'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->placeholder('32')
                                    ->live(debounce: 500),
                                TextInput::make('nav_design.header.links_gap')
                                    ->label(__('laravix::navigation.design.links_gap'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->placeholder('24')
                                    ->live(debounce: 500),
                                Select::make('nav_design.header.links_align')
                                    ->label(__('laravix::navigation.design.links_align'))
                                    ->options($linksAlignOptions)
                                    ->live(),
                                Select::make('nav_design.header.icon_position')
                                    ->label(__('laravix::navigation.design.icon_position'))
                                    ->options($iconPositionOptions)
                                    ->live(),
                            ])
                            ->columns(4),

                        Section::make(__('laravix::navigation.design.section_dropdown'))
                            ->schema([
                                $colorInput('nav_design.header.dropdown_bg', __('laravix::navigation.design.dropdown_bg')),
                                $colorInput('nav_design.header.dropdown_text', __('laravix::navigation.design.dropdown_text')),
                                $colorInput('nav_design.header.dropdown_hover_bg', __('laravix::navigation.design.dropdown_hover_bg')),
                            ])
                            ->columns(3),

                        Section::make(__('laravix::navigation.design.section_behavior'))
                            ->schema([
                                Toggle::make('nav_design.header.sticky')
                                    ->label(__('laravix::navigation.design.sticky'))
                                    ->default(true)
                                    ->live(),
                                TextInput::make('nav_design.header.bg_opacity')
                                    ->label(__('laravix::navigation.design.bg_opacity'))
                                    ->type('range')
                                    ->extraInputAttributes([
                                        'min' => '0',
                                        'max' => '100',
                                        'step' => '1',
                                        'class' => 'w-full accent-primary-600',
                                    ])
                                    ->helperText(fn ($state) => ($state ?? 100).'%')
                                    ->default(100)
                                    ->live(debounce: 100),
                            ])
                            ->columns(2),

                    ])
                    ->collapsible(),
            ]);
    }

    public function footerDesignForm(Schema $schema): Schema
    {
        [$colorInput, $fontFamilyOptions, $fontWeightOptions,,,, $footerLayoutOptions, $iconPositionOptions] = $this->designFormSharedOptions();

        return $schema
            ->statePath('footerDesignData')
            ->components([
                Section::make(__('laravix::navigation.labels.footer_design'))
                    ->schema([

                        Section::make(__('laravix::navigation.design.section_colors'))
                            ->schema([
                                $colorInput('nav_design.footer.bg_color', __('laravix::navigation.design.bg_color')),
                                $colorInput('nav_design.footer.text_color', __('laravix::navigation.design.text_color')),
                                $colorInput('nav_design.footer.hover_color', __('laravix::navigation.design.hover_color')),
                                $colorInput('nav_design.footer.border_color', __('laravix::navigation.design.border_color')),
                            ])
                            ->columns(4),

                        Section::make(__('laravix::navigation.design.section_typography'))
                            ->schema([
                                Select::make('nav_design.footer.font_family')
                                    ->label(__('laravix::navigation.design.font_family'))
                                    ->options($fontFamilyOptions)
                                    ->columnSpan(2)
                                    ->live(),
                                TextInput::make('nav_design.footer.font_size')
                                    ->label(__('laravix::navigation.design.font_size'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->live(debounce: 500),
                                Select::make('nav_design.footer.font_weight')
                                    ->label(__('laravix::navigation.design.font_weight'))
                                    ->options($fontWeightOptions)
                                    ->live(),
                            ])
                            ->columns(4),

                        Section::make(__('laravix::navigation.design.section_layout'))
                            ->schema([
                                TextInput::make('nav_design.footer.padding_y')
                                    ->label(__('laravix::navigation.design.padding_y'))
                                    ->numeric()
                                    ->suffix('px')
                                    ->placeholder('32')
                                    ->live(debounce: 500),
                                Select::make('nav_design.footer.layout')
                                    ->label(__('laravix::navigation.design.layout'))
                                    ->options($footerLayoutOptions)
                                    ->live(),
                                Select::make('nav_design.footer.icon_position')
                                    ->label(__('laravix::navigation.design.icon_position'))
                                    ->options($iconPositionOptions)
                                    ->live(),
                            ])
                            ->columns(3),

                        Section::make(__('laravix::navigation.design.section_content'))
                            ->schema([
                                TextInput::make('nav_design.footer.copyright_text')
                                    ->label(__('laravix::navigation.design.copyright_text'))
                                    ->columnSpan(3)
                                    ->live(debounce: 500),
                                Toggle::make('nav_design.footer.show_copyright')
                                    ->label(__('laravix::navigation.design.show_copyright'))
                                    ->default(true)
                                    ->live(),
                            ])
                            ->columns(4),

                    ])
                    ->collapsible(),
            ]);
    }

    protected function getForms(): array
    {
        return ['headerForm', 'footerForm', 'headerDesignForm', 'footerDesignForm'];
    }

    public function save(): void
    {
        $site = $this->site ?? filament()->getTenant();

        $site->update([
            'navigations' => array_merge(
                $this->headerForm->getState()['navigations'] ?? [],
                $this->footerForm->getState()['navigations'] ?? [],
            ),
            'nav_design' => array_merge(
                $this->headerDesignForm->getState()['nav_design'] ?? [],
                $this->footerDesignForm->getState()['nav_design'] ?? [],
            ),
        ]);

        $this->refreshPreview();

        Notification::make()
            ->title(__('laravix::navigation.messages.saved'))
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('laravix::navigation.actions.save'))
                ->action('save'),
        ];
    }

    private function designFormSharedOptions(): array
    {
        $colorInput = fn (string $key, string $label) => TextInput::make($key)
            ->label($label)
            ->type('color')
            ->extraInputAttributes(['style' => 'height:40px;padding:2px 4px;cursor:pointer'])
            ->live(debounce: 500);

        $fontFamilyOptions = [
            __('laravix::navigation.design.font_group_system') => [
                '' => __('laravix::navigation.design.font_system'),
                'ui-sans-serif, system-ui, sans-serif' => 'Sans-serif',
                'ui-serif, Georgia, serif' => 'Serif',
                'ui-monospace, monospace' => 'Monospace',
                'Arial, sans-serif' => 'Arial',
                'Verdana, sans-serif' => 'Verdana',
                'Georgia, serif' => 'Georgia',
                'Trebuchet MS, sans-serif' => 'Trebuchet MS',
                'Times New Roman, serif' => 'Times New Roman',
            ],
            __('laravix::navigation.design.font_group_sans') => [
                'Inter, sans-serif' => 'Inter',
                'Roboto, sans-serif' => 'Roboto',
                'Open Sans, sans-serif' => 'Open Sans',
                'Lato, sans-serif' => 'Lato',
                'Montserrat, sans-serif' => 'Montserrat',
                'Poppins, sans-serif' => 'Poppins',
                'Nunito, sans-serif' => 'Nunito',
                'Raleway, sans-serif' => 'Raleway',
                'Ubuntu, sans-serif' => 'Ubuntu',
                'Rubik, sans-serif' => 'Rubik',
                'Work Sans, sans-serif' => 'Work Sans',
                'DM Sans, sans-serif' => 'DM Sans',
                'Noto Sans, sans-serif' => 'Noto Sans',
                'Source Sans 3, sans-serif' => 'Source Sans 3',
                'Manrope, sans-serif' => 'Manrope',
                'Outfit, sans-serif' => 'Outfit',
                'Plus Jakarta Sans, sans-serif' => 'Plus Jakarta Sans',
            ],
            __('laravix::navigation.design.font_group_serif') => [
                'Playfair Display, serif' => 'Playfair Display',
                'Merriweather, serif' => 'Merriweather',
                'Lora, serif' => 'Lora',
                'PT Serif, serif' => 'PT Serif',
                'Libre Baskerville, serif' => 'Libre Baskerville',
                'EB Garamond, serif' => 'EB Garamond',
                'Cormorant Garamond, serif' => 'Cormorant Garamond',
                'Crimson Text, serif' => 'Crimson Text',
            ],
        ];

        $fontWeightOptions = [
            '300' => __('laravix::navigation.design.weight_light'),
            '400' => __('laravix::navigation.design.weight_normal'),
            '500' => __('laravix::navigation.design.weight_medium'),
            '600' => __('laravix::navigation.design.weight_semibold'),
            '700' => __('laravix::navigation.design.weight_bold'),
        ];

        $borderWidthOptions = [
            '0px' => __('laravix::navigation.design.border_none'),
            '1px' => '1px',
            '2px' => '2px',
            '3px' => '3px',
        ];

        $shadowOptions = [
            '' => __('laravix::navigation.design.shadow_none'),
            'shadow_sm' => __('laravix::navigation.design.shadow_sm'),
            'shadow_md' => __('laravix::navigation.design.shadow_md'),
            'shadow_lg' => __('laravix::navigation.design.shadow_lg'),
        ];

        $linksAlignOptions = [
            'flex-end' => __('laravix::navigation.design.align_right'),
            'center' => __('laravix::navigation.design.align_center'),
            'flex-start' => __('laravix::navigation.design.align_left'),
        ];

        $footerLayoutOptions = [
            'row' => __('laravix::navigation.design.footer_layout_row'),
            'stacked' => __('laravix::navigation.design.footer_layout_stacked'),
        ];

        $iconPositionOptions = [
            '' => __('laravix::navigation.design.icon_none'),
            'before' => __('laravix::navigation.design.icon_pos_before'),
            'after' => __('laravix::navigation.design.icon_pos_after'),
            'only' => __('laravix::navigation.design.icon_pos_only'),
        ];

        return [$colorInput, $fontFamilyOptions, $fontWeightOptions, $borderWidthOptions, $shadowOptions, $linksAlignOptions, $footerLayoutOptions, $iconPositionOptions];
    }
}
