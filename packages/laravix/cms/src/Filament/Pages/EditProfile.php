<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Pages;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Js;

class EditProfile extends BaseEditProfile
{
    public function getMaxWidth(): Width|string|null
    {
        return Width::ScreenLarge;
    }

    public function form(Schema $schema): Schema
    {
        $url = filament()->getUrl();

        return $schema
            ->components([
                Actions::make([
                    Action::make('back')
                        ->label(__('laravix::common.back'))
                        ->color('gray')
                        ->alpineClickHandler(
                            FilamentView::hasSpaMode($url)
                                ? 'document.referrer ? window.history.back() : Livewire.navigate('.Js::from($url).')'
                                : 'document.referrer ? window.history.back() : (window.location.href = '.Js::from($url).')',
                        ),
                ])->columnSpanFull(),
                Tabs::make()
                    ->vertical()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('laravix::profile.tabs.profile'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        FileUpload::make('avatar')
                                            ->label(__('laravix::profile.fields.avatar'))
                                            ->image()
                                            ->imageEditor()
                                            ->circleCropper()
                                            ->disk('public')
                                            ->directory('avatars')
                                            ->maxSize(2048)
                                            ->avatar()
                                            ->columnSpanFull(),
                                        $this->getNameFormComponent(),
                                    ]),
                            ]),
                        Tab::make(__('laravix::profile.tabs.security'))
                            ->schema([
                                Section::make()
                                    ->schema([
                                        $this->getEmailFormComponent(),
                                        $this->getPasswordFormComponent(),
                                        $this->getPasswordConfirmationFormComponent(),
                                        $this->getCurrentPasswordFormComponent(),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
