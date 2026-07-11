<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Users\UserResource;
use Laravix\Cms\Mail\UserInvitationMail;
use Laravix\Cms\Models\UserInvitation;

class CreateUser extends Page
{
    protected static string $resource = UserResource::class;

    protected string $view = 'laravix::filament.resources.users.pages.create-user';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make(__('laravix::users.sections.invite'))
                    ->description(__('laravix::users.messages.will_receive_email'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label(__('laravix::common.email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->options(collect(SiteRole::cases())->mapWithKeys(
                                fn (SiteRole $case) => [$case->value => $case->name]
                            ))
                            ->required()
                            ->helperText(__('laravix::users.messages.role_determines')),
                    ]),
            ]);
    }

    public function invite(): void
    {
        $data = $this->form->getState();

        $invitation = UserInvitation::create([
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => UserInvitation::generateToken(),
            'site_id' => Filament::getTenant()->id,
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(1),
        ]);

        Mail::to($invitation->email)->send(new UserInvitationMail($invitation));

        Notification::make()
            ->title(__('laravix::users.messages.invitation_sent', ['email' => $invitation->email]))
            ->success()
            ->send();

        $this->redirect(UserResource::getUrl('index'));
    }
}
