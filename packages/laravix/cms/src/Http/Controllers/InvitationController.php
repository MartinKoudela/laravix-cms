<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravix\Cms\Models\User;
use Laravix\Cms\Models\UserInvitation;

class InvitationController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $invitation = UserInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isAccepted()) {
            return redirect()->route('filament.admin.auth.login')
                ->with('status', 'This invitation has already been accepted.');
        }

        if ($invitation->isExpired()) {
            abort(410, 'This invitation has expired.');
        }

        return view('laravix::invitation.accept', compact('invitation'));
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $invitation = UserInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isAccepted() || $invitation->isExpired()) {
            abort(410);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->sites()->attach($invitation->site_id, ['role' => $invitation->role]);

        $invitation->update(['accepted_at' => now()]);

        Auth::login($user);

        return redirect('/admin/'.$invitation->site_id);
    }
}
