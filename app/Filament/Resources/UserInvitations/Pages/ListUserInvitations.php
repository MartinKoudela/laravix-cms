<?php

namespace App\Filament\Resources\UserInvitations\Pages;

use App\Filament\Resources\UserInvitations\UserInvitationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserInvitations extends ListRecords
{
    protected static string $resource = UserInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
