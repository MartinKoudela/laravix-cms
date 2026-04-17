<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\SiteRole;
use App\Filament\Resources\Users\UserResource;
use App\Mail\UserInvitationMail;
use App\Models\UserInvitation;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;

class CreateUser extends Page
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.users.pages.create-user';

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
                Section::make('Invite User')
                    ->description('The user will receive an email with a link to set up their account.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->options(collect(SiteRole::cases())->mapWithKeys(
                                fn (SiteRole $case) => [$case->value => $case->name]
                            ))
                            ->required()
                            ->helperText('Role determines what the user can do within this site.'),
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
            ->title('Invitation sent to ' . $invitation->email)
            ->success()
            ->send();

        $this->redirect(UserResource::getUrl('index'));
    }
}
