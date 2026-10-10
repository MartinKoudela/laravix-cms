@php
    use Laravix\Cms\Support\NavigationIconRegistry;

    $footerDesign = collect($navDesign['footer'] ?? []);
    $showCopyright = $footerDesign->get('show_copyright') === null || (bool) $footerDesign->get('show_copyright');
    $copyright = $footerDesign->get('copyright_text') ?: $site->name;
    $isStacked = $footerDesign->get('layout') === 'stacked';
    $iconPosition = $footerDesign->get('icon_position') ?: '';
    $siteName = $settings->get('site_name', $site->name);

    $href = fn (?string $url) => ($url && ! preg_match('~^(https?://|/|mailto:|tel:|#)~', $url)) ? 'https://'.$url : ($url ?? '');

    $label = function (array $item) use ($iconPosition): string {
        $icon = ($iconPosition && ! empty($item['icon'])) ? NavigationIconRegistry::renderSvg($item['icon']) : '';
        $text = '<span>'.e($item['label']).'</span>';

        return match ($iconPosition) {
            'before' => $icon.$text,
            'after' => $text.$icon,
            'only' => $icon ?: $text,
            default => $text,
        };
    };

    $socials = collect([
        'twitter_url' => ['fa-x-twitter', 'X'],
        'instagram_url' => ['fa-instagram', 'Instagram'],
        'facebook_url' => ['fa-facebook-f', 'Facebook'],
        'linkedin_url' => ['fa-linkedin-in', 'LinkedIn'],
        'github_url' => ['fa-github', 'GitHub'],
        'youtube_url' => ['fa-youtube', 'YouTube'],
        'tiktok_url' => ['fa-tiktok', 'TikTok'],
        'discord_url' => ['fa-discord', 'Discord'],
        'telegram_url' => ['fa-telegram', 'Telegram'],
        'whatsapp_url' => ['fa-whatsapp', 'WhatsApp'],
        'pinterest_url' => ['fa-pinterest-p', 'Pinterest'],
        'reddit_url' => ['fa-reddit-alien', 'Reddit'],
        'twitch_url' => ['fa-twitch', 'Twitch'],
        'snapchat_url' => ['fa-snapchat', 'Snapchat'],
        'spotify_url' => ['fa-spotify', 'Spotify'],
    ])
        ->map(fn (array $meta, string $key) => ['url' => $settings->get($key), 'icon' => $meta[0], 'name' => $meta[1]])
        ->filter(fn (array $social) => filled($social['url']));
@endphp

<footer class="noir-footer">
    <div class="noir-shell">
        <div class="noir-footer__top {{ $isStacked ? 'is-stacked' : '' }}">
            <div class="noir-footer__brand">
                <a href="/" class="noir-footer__wordmark">{{ $siteName }}<span class="noir-header__dot" aria-hidden="true"></span></a>

                @if ($description = $settings->get('site_description'))
                    <p class="noir-footer__description">{{ $description }}</p>
                @endif

                @if ($socials->isNotEmpty())
                    <ul class="noir-footer__socials" aria-label="{{ __('laravix::theme.follow') }}">
                        @foreach ($socials as $social)
                            <li>
                                <a href="{{ $href($social['url']) }}" target="_blank" rel="noopener" class="noir-footer__social" aria-label="{{ $social['name'] }}">
                                    <i class="fa-brands {{ $social['icon'] }}" aria-hidden="true"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            @if (! empty($navigations['footer']))
                <nav class="noir-footer__nav" aria-label="{{ $siteName }}">
                    @foreach ($navigations['footer'] as $item)
                        <a href="{{ $href($item['url']) }}" target="{{ $item['target'] ?? '_self' }}" class="noir-footer__link nav-link">
                            {!! $label($item) !!}
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>

        <div class="noir-footer__bottom">
            @if ($showCopyright)
                <p class="noir-footer__copyright">&copy; {{ date('Y') }} {{ $copyright }}</p>
            @endif

            <a href="#top" class="noir-footer__top-link">
                {{ __('laravix::theme.back_to_top') }}
                <svg viewBox="0 0 12 12" aria-hidden="true"><path d="M6 10V2M2.5 5.5 6 2l3.5 3.5" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
            </a>
        </div>
    </div>
</footer>
