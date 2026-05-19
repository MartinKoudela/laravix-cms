<?php

namespace App\Support;

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
        ];
    }

    public static function selectOptions(): array
    {
        $options = [];

        foreach (static::groups() as $group => $icons) {
            $options[$group] = [];
            foreach ($icons as $name) {
                $svgHtml = svg('heroicon-o-'.$name, '', ['style' => 'width:20px;height:20px;flex-shrink:0'])->toHtml();
                $label = e(ucwords(str_replace('-', ' ', $name)));
                $options[$group][$name] = "<span style=\"display:inline-flex;align-items:center;gap:8px\">{$svgHtml}<span style=\"font-size:13px\">{$label}</span></span>";
            }
        }

        return $options;
    }

    public static function renderSvg(string $name, string $class = 'inline w-4 h-4 flex-shrink-0'): string
    {
        return svg('heroicon-o-'.$name, '', ['style' => 'width:1.1em;height:1.1em;flex-shrink:0;vertical-align:middle;display:inline-block'])->toHtml();
    }
}
