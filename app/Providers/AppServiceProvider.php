<?php

namespace App\Providers;

use App\Enums\FieldType;
use App\Support\FieldDefinition;
use App\Support\FieldRegistry;
use App\Support\SettingDefinition;
use App\Support\SettingRegistry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        FieldRegistry::content([
            FieldDefinition::make('body')->type(FieldType::RICH_TEXT)->label('Body'),
            FieldDefinition::make('hero_image')->type(FieldType::IMAGE)->label('Hero Image'),
            FieldDefinition::make('excerpt')->type(FieldType::TEXTAREA)->label('Excerpt'),

            FieldDefinition::make('meta_title')
                ->label('Meta Title')
                ->group('SEO')
                ->hint('Overrides the site-wide default when set.'),
            FieldDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('Meta Description')
                ->group('SEO')
                ->hint('Up to 160 characters. Overrides the site-wide default.'),
            FieldDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('OG Image')
                ->group('SEO')
                ->hint('Overrides the site-wide OG image for this page.'),
            FieldDefinition::make('noindex')
                ->type(FieldType::BOOLEAN)
                ->label('Hide from search engines')
                ->group('SEO'),
        ]);

        SettingRegistry::register([
            SettingDefinition::make('site_name')
                ->label('Site Name')
                ->group('General')
                ->required(),
            SettingDefinition::make('site_description')
                ->type(FieldType::TEXTAREA)
                ->label('Site Description')
                ->group('General'),
            SettingDefinition::make('logo')
                ->type(FieldType::IMAGE)
                ->label('Site Logo')
                ->group('General')
                ->hint('Displayed in the header and footer.'),
            SettingDefinition::make('favicon')
                ->type(FieldType::IMAGE)
                ->label('Favicon')
                ->group('General')
                ->hint('Browser tab icon. Recommended: 32×32 or 64×64 PNG.'),
            SettingDefinition::make('locale')
                ->label('Locale')
                ->group('General')
                ->default('en')
                ->hint('Language code used in <html lang="">. E.g. en, cs, de, fr.'),
            SettingDefinition::make('contact_email')
                ->label('Contact Email')
                ->group('General')
                ->hint('Used in contact forms and transactional emails.')
                ->config(['email' => true]),

            SettingDefinition::make('meta_title')
                ->label('Meta Title')
                ->group('SEO')
                ->hint('Default page title used when no content-level title is set.'),
            SettingDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('Meta Description')
                ->group('SEO')
                ->hint('Default meta description (up to 160 characters).')
                ->config(['maxLength' => 160]),
            SettingDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('OG Image')
                ->group('SEO')
                ->hint('Default Open Graph image for social sharing.'),
            SettingDefinition::make('google_site_verification')
                ->label('Google Site Verification')
                ->group('SEO')
                ->hint('Paste the content value from the Google Search Console meta tag.'),
            SettingDefinition::make('twitter_url')
                ->type(FieldType::URL)
                ->label('X / Twitter')
                ->group('Social'),
            SettingDefinition::make('linkedin_url')
                ->type(FieldType::URL)
                ->label('LinkedIn')
                ->group('Social'),
            SettingDefinition::make('facebook_url')
                ->type(FieldType::URL)
                ->label('Facebook')
                ->group('Social'),
            SettingDefinition::make('instagram_url')
                ->type(FieldType::URL)
                ->label('Instagram')
                ->group('Social'),
            SettingDefinition::make('github_url')
                ->type(FieldType::URL)
                ->label('GitHub')
                ->group('Social'),
            SettingDefinition::make('')

        ]);

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            View::addNamespace("themes.{$themeName}", "{$themePath}/views");
        }
    }
}
