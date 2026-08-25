<?php

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\Support\Htmlable;
use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Support\NavigationIconRegistry;
use Laravix\Cms\Support\SettingComponentFactory;
use Laravix\Cms\Support\SettingDefinition;
use Laravix\Cms\Support\SettingRegistry;

dataset('social settings', [
    'twitter' => ['twitter_url', 'fa-x-twitter'],
    'linkedin' => ['linkedin_url', 'fa-linkedin'],
    'facebook' => ['facebook_url', 'fa-facebook'],
    'instagram' => ['instagram_url', 'fa-instagram'],
    'tiktok' => ['tiktok_url', 'fa-tiktok'],
    'github' => ['github_url', 'fa-github'],
    'youtube' => ['youtube_url', 'fa-youtube'],
    'discord' => ['discord_url', 'fa-discord'],
    'telegram' => ['telegram_url', 'fa-telegram'],
    'whatsapp' => ['whatsapp_url', 'fa-whatsapp'],
    'pinterest' => ['pinterest_url', 'fa-pinterest'],
    'reddit' => ['reddit_url', 'fa-reddit'],
    'twitch' => ['twitch_url', 'fa-twitch'],
    'snapchat' => ['snapchat_url', 'fa-snapchat'],
    'spotify' => ['spotify_url', 'fa-spotify'],
]);

function socialSetting(string $key): SettingDefinition
{
    return collect(SettingRegistry::all())->firstOrFail(
        fn (SettingDefinition $definition): bool => $definition->key === $key
    );
}

test('every social setting carries a brand icon and no misleading url prefix', function (string $key, string $faClass) {
    $definition = socialSetting($key);

    expect($definition->config['prefixIcon'] ?? null)->not->toBeNull()
        ->and($definition->config)->not->toHaveKey('prefix');
})->with('social settings');

test('the rendered input shows the brand logo', function (string $key, string $faClass) {
    $component = SettingComponentFactory::make(socialSetting($key));

    expect($component)->toBeInstanceOf(TextInput::class)
        ->and($component->getPrefixLabel())->toBeNull();

    $icon = $component->getPrefixIcon();

    expect($icon)->toBeInstanceOf(Htmlable::class)
        ->and($icon->toHtml())->toContain($faClass)
        ->and($icon->toHtml())->toContain('fa-brands');
})->with('social settings');

test('chained config calls accumulate instead of overwriting each other', function () {
    $definition = SettingDefinition::make('example')
        ->config(['prefix' => 'https://example.com/'])
        ->config(['prefixIcon' => 'fa-github']);

    expect($definition->config)->toBe([
        'prefix' => 'https://example.com/',
        'prefixIcon' => 'fa-github',
    ]);
});

test('a later config call still overrides the same key', function () {
    $definition = SettingDefinition::make('example')
        ->config(['prefix' => 'https://example.com/'])
        ->config(['prefix' => 'https://laravix.com/']);

    expect($definition->config['prefix'])->toBe('https://laravix.com/');
});

test('a non-brand icon key is passed through to filament untouched', function () {
    $definition = SettingDefinition::make('example')
        ->config(['prefixIcon' => 'heroicon-o-link']);

    expect(SettingComponentFactory::make($definition)->getPrefixIcon())->toBe('heroicon-o-link');
});

test('affixes are skipped for components that cannot render them', function () {
    $definition = SettingDefinition::make('example')
        ->type(FieldType::BOOLEAN)
        ->config(['prefix' => 'https://example.com/', 'prefixIcon' => 'fa-github']);

    expect(SettingComponentFactory::make($definition))->toBeInstanceOf(Toggle::class);
});

test('every brand icon key used by a setting resolves to a real font awesome class', function (string $key, string $faClass) {
    $iconKey = socialSetting($key)->config['prefixIcon'];

    expect(NavigationIconRegistry::brandIconClass($iconKey))->toBe('fa-brands '.$faClass);
})->with('social settings');
