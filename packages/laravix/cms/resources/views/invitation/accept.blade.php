<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('laravix::invitations.accept.title') }} — {{ $invitation->site->name }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ \Laravix\Cms\Laravix::asset('app.css') }}">
    <script type="module" src="{{ \Laravix\Cms\Laravix::asset('frontend.js') }}"></script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <div class="flex justify-center mb-8">
            <img src="{{ asset('laravix-logo-black.svg') }}" alt="{{ config('app.name') }}" class="h-9 w-auto dark:hidden">
            <img src="{{ asset('laravix-logo-white.svg') }}" alt="{{ config('app.name') }}" class="h-9 w-auto hidden dark:block">
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm ring-1 ring-zinc-950/5 dark:ring-white/10 p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                {{ __('laravix::invitations.accept.title') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                {!! __('laravix::invitations.accept.intro', [
                    'site' => '<strong class="font-medium text-zinc-700 dark:text-zinc-200">'.e($invitation->site->name).'</strong>',
                    'role' => '<strong class="font-medium text-zinc-700 dark:text-zinc-200">'.e(__('laravix::users.roles.'.$invitation->role)).'</strong>',
                ]) !!}
            </p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-700 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-900">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.accept.submit', $invitation->token) }}" class="mt-6 space-y-5">
                @csrf

                @foreach ([
                    ['name', 'text', 'name', 'given-name'],
                    ['password', 'password', 'password', 'new-password'],
                    ['password_confirmation', 'password', 'password_confirmation', 'new-password'],
                ] as [$field, $type, $key, $autocomplete])
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                            {{ __('laravix::invitations.accept.'.$key) }}
                        </label>
                        <input
                            type="{{ $type }}"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            @if ($type === 'text') value="{{ old($field) }}" @endif
                            autocomplete="{{ $autocomplete }}"
                            required
                            class="block w-full rounded-lg border-0 bg-white dark:bg-zinc-950 px-3 py-2 text-sm text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#ff0465] transition"
                        >
                    </div>
                @endforeach

                <button
                    type="submit"
                    class="w-full rounded-lg bg-linear-to-r from-[#ff0465] to-[#ff6602] px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[#ff0465] focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
                >
                    {{ __('laravix::invitations.accept.submit') }}
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
            {{ __('laravix::invitations.accept.expires', ['time' => $invitation->expires_at->diffForHumans()]) }}
        </p>
    </div>
</body>
</html>
