<?php

namespace App\Policies;

use App\Enums\SiteRole;
use App\Models\Content;
use App\Models\Site;
use App\Models\User;

class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Content $content): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($content->site) !== null;
    }

    public function create(User $user): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        $site = filament()->getTenant();

        return $site instanceof Site
            && in_array($user->roleForSite($site), [SiteRole::ADMIN, SiteRole::EDITOR]);
    }

    public function deleteAny(User $user): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        $site = filament()->getTenant();

        return $site instanceof Site
            && $user->roleForSite($site) === SiteRole::ADMIN;
    }

    public function update(User $user, Content $content): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return in_array($user->roleForSite($content->site),
            [SiteRole::ADMIN, SiteRole::EDITOR]);
    }

    public function delete(User $user, Content $content): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($content->site) === SiteRole::ADMIN;
    }
}
