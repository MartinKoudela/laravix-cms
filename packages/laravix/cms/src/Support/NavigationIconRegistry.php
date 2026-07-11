<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

class NavigationIconRegistry
{
    public static function groups(): array
    {
        return [
            'Navigation & UI' => [
                'home', 'magnifying-glass', 'bars-3', 'x-mark',
                'arrow-right', 'arrow-long-right', 'chevron-right', 'chevron-double-right',
            ],
            'People & Contact' => [
                'user', 'user-group', 'envelope', 'phone',
                'chat-bubble-left-ellipsis', 'at-symbol',
            ],
            'Commerce' => [
                'shopping-cart', 'shopping-bag', 'credit-card',
                'tag', 'gift', 'percent-badge', 'cube',
            ],
            'Content & Media' => [
                'document-text', 'book-open', 'newspaper', 'photo',
                'film', 'video-camera', 'microphone', 'rss',
            ],
            'Places & Business' => [
                'building-office', 'map-pin', 'globe-alt',
                'briefcase', 'academic-cap', 'truck', 'rocket-launch',
            ],
            'Calendar & Alerts' => [
                'calendar', 'clock', 'bell', 'light-bulb',
            ],
            'Other' => [
                'star', 'heart', 'bookmark', 'cog-6-tooth',
                'information-circle', 'question-mark-circle', 'check-circle',
                'shield-check', 'fire', 'code-bracket', 'link', 'folder',
            ],
            'Social Media' => array_keys(static::socialIcons()),
        ];
    }

    public static function selectOptions(): array
    {
        $social = static::socialIcons();
        $options = [];

        foreach (static::groups() as $group => $icons) {
            $options[$group] = [];
            foreach ($icons as $name) {
                if (isset($social[$name])) {
                    [$faClass, $label] = $social[$name];
                    $iconHtml = '<i class="'.$faClass.'" style="font-size:20px;width:20px;text-align:center;flex-shrink:0"></i>';
                } else {
                    $iconHtml = svg('heroicon-o-'.$name, '', ['style' => 'width:20px;height:20px;flex-shrink:0'])->toHtml();
                    $label = ucwords(str_replace('-', ' ', $name));
                }
                $options[$group][$name] = '<span style="display:inline-flex;align-items:center;gap:8px">'.$iconHtml.'<span style="font-size:13px">'.e($label).'</span></span>';
            }
        }

        return $options;
    }

    public static function renderSvg(string $name): string
    {
        $social = static::socialIcons();
        $style = 'width:1.1em;height:1.1em;flex-shrink:0;vertical-align:middle;display:inline-block';

        if (isset($social[$name])) {
            [$faClass] = $social[$name];

            return '<i class="'.$faClass.'" style="font-size:1.1em;flex-shrink:0;vertical-align:middle"></i>';
        }

        return svg('heroicon-o-'.$name, '', ['style' => $style])->toHtml();
    }

    /** @return array<string, array{0: string, 1: string}> name => [fa-class, label] */
    private static function socialIcons(): array
    {
        return [
            'fa-twitter-x' => ['fa-brands fa-x-twitter', 'Twitter / X'],
            'fa-facebook' => ['fa-brands fa-facebook', 'Facebook'],
            'fa-instagram' => ['fa-brands fa-instagram', 'Instagram'],
            'fa-linkedin' => ['fa-brands fa-linkedin', 'LinkedIn'],
            'fa-github' => ['fa-brands fa-github', 'GitHub'],
            'fa-youtube' => ['fa-brands fa-youtube', 'YouTube'],
            'fa-tiktok' => ['fa-brands fa-tiktok', 'TikTok'],
            'fa-discord' => ['fa-brands fa-discord', 'Discord'],
            'fa-pinterest' => ['fa-brands fa-pinterest', 'Pinterest'],
            'fa-reddit' => ['fa-brands fa-reddit', 'Reddit'],
            'fa-twitch' => ['fa-brands fa-twitch', 'Twitch'],
            'fa-whatsapp' => ['fa-brands fa-whatsapp', 'WhatsApp'],
            'fa-telegram' => ['fa-brands fa-telegram', 'Telegram'],
            'fa-snapchat' => ['fa-brands fa-snapchat', 'Snapchat'],
            'fa-spotify' => ['fa-brands fa-spotify', 'Spotify'],
        ];
    }
}
