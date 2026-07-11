<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[Signature('laravix:user {--name=} {--email=} {--password=} {--super : Create a super admin with access to all sites}')]
#[Description('Create a user that can log into the admin panel.')]
class CreateUser extends Command
{
    public function handle(): int
    {
        $name = $this->option('name') ?: text('Name', required: true);
        $email = $this->option('email') ?: text('Email', required: true, validate: fn (string $value) => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email address.');

        if (User::where('email', $email)->exists()) {
            $this->error("A user with email {$email} already exists.");

            return self::FAILURE;
        }

        $plainPassword = $this->option('password') ?: password('Password', required: true, validate: fn (string $value) => strlen($value) >= 8 ? null : 'Password must be at least 8 characters.');

        $isSuper = $this->option('super') || confirm('Super admin (access to all sites)?', default: ! Site::exists());

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($plainPassword),
        ]);
        $user->is_super_admin = $isSuper;
        $user->save();

        if (! $isSuper) {
            if (! Site::exists()) {
                $this->warn('No sites exist yet — the user will not be able to log in until they are assigned to a site or made super admin.');
            } else {
                $siteId = select(
                    'Assign to site',
                    Site::pluck('name', 'id')->all(),
                );

                $role = select(
                    'Role',
                    collect(SiteRole::cases())->mapWithKeys(fn (SiteRole $r) => [$r->value => ucfirst($r->value)])->all(),
                    default: SiteRole::ADMIN->value,
                );

                $user->sites()->attach($siteId, ['role' => $role]);
            }
        }

        $this->info("User {$email} created".($isSuper ? ' as super admin' : '').'.');

        return self::SUCCESS;
    }
}
