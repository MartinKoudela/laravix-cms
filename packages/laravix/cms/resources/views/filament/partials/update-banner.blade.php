@php
    $checker = app(\Laravix\Cms\Services\UpdateChecker::class);
@endphp

@if (auth()->user()?->isAdmin() && $checker->updateAvailable())
    <div class="fi-update-banner" style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:linear-gradient(135deg,#ff0465 0%,#ff6602 100%);color:#fff;font-size:13px;font-weight:500;">
        <span>{{ __('laravix::panel.update.available', ['version' => $checker->latestVersion()]) }}</span>
        <code style="background:rgba(255,255,255,.15);padding:2px 8px;border-radius:4px;">php artisan laravix:upgrade</code>
    </div>
@endif
