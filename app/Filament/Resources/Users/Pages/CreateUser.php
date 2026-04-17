<?php

namespace App\Filament\Resources\Users\Pages;

use App\Enums\SiteRole;
use App\Filament\Resources\Users\UserResource;
use App\Models\UserInvitation;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

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
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('role')
                    ->options(collect(SiteRole::cases())->mapWithKeys(
                        fn (SiteRole $case) => [$case->value => $case->name]
                    ))
                    ->required(),
            ]);
    }

    public function invite(): void
    {
        $data = $this->form->getState();

        UserInvitation::create([
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => UserInvitation::generateToken(),
            'site_id' => Filament::getTenant()->id,
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        Notification::make()
            ->title('Invitation sent')
            ->success()
            ->send();

        $this->redirect(UserResource::getUrl('index'));
    }
}
