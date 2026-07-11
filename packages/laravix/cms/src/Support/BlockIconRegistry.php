<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

class BlockIconRegistry
{
    public static function selectOptions(): array
    {
        $options = [];

        foreach (static::groups() as $group => $icons) {
            $options[$group] = [];
            foreach ($icons as $name => $label) {
                $iconHtml = '<i class="fa-solid fa-'.$name.'" style="font-size:16px;width:18px;text-align:center;flex-shrink:0"></i>';
                $options[$group][$name] = '<span style="display:inline-flex;align-items:center;gap:8px">'.$iconHtml.'<span style="font-size:13px">'.e($label).'</span></span>';
            }
        }

        return $options;
    }

    private static function groups(): array
    {
        return [
            'Code & Dev' => [
                'brackets-curly'  => 'Brackets Curly',
                'code'            => 'Code',
                'code-branch'     => 'Code Branch',
                'terminal'        => 'Terminal',
                'bug'             => 'Bug',
                'database'        => 'Database',
                'server'          => 'Server',
            ],
            'Layout & UI' => [
                'table-columns'   => 'Columns',
                'table-cells'     => 'Grid',
                'layer-group'     => 'Layers',
                'window-restore'  => 'Window',
                'border-all'      => 'Border',
                'object-group'    => 'Group',
                'puzzle-piece'    => 'Puzzle',
            ],
            'Content & Media' => [
                'align-left'      => 'Text',
                'heading'         => 'Heading',
                'image'           => 'Image',
                'film'            => 'Film',
                'video'           => 'Video',
                'music'           => 'Music',
                'microphone'      => 'Microphone',
                'photo-film'      => 'Photo Film',
            ],
            'Navigation & Links' => [
                'house'           => 'House',
                'link'            => 'Link',
                'arrow-right'     => 'Arrow Right',
                'bars'            => 'Menu',
                'list'            => 'List',
                'sitemap'         => 'Sitemap',
            ],
            'Commerce' => [
                'cart-shopping'   => 'Cart',
                'tag'             => 'Tag',
                'gift'            => 'Gift',
                'percent'         => 'Percent',
                'money-bill'      => 'Money',
                'credit-card'     => 'Credit Card',
                'bag-shopping'    => 'Shopping Bag',
            ],
            'Communication' => [
                'envelope'        => 'Envelope',
                'phone'           => 'Phone',
                'comment'         => 'Comment',
                'comments'        => 'Comments',
                'bell'            => 'Bell',
                'share-nodes'     => 'Share',
            ],
            'Data & Charts' => [
                'chart-bar'       => 'Bar Chart',
                'chart-line'      => 'Line Chart',
                'chart-pie'       => 'Pie Chart',
                'table'           => 'Table',
                'filter'          => 'Filter',
                'magnifying-glass'=> 'Search',
            ],
            'Design' => [
                'palette'         => 'Palette',
                'paintbrush'      => 'Paintbrush',
                'pen'             => 'Pen',
                'pen-to-square'   => 'Edit',
                'wand-magic-sparkles' => 'Magic',
                'crop'            => 'Crop',
                'sliders'         => 'Sliders',
            ],
            'General' => [
                'star'            => 'Star',
                'heart'           => 'Heart',
                'bookmark'        => 'Bookmark',
                'flag'            => 'Flag',
                'bolt'            => 'Bolt',
                'fire'            => 'Fire',
                'gear'            => 'Gear',
                'circle-info'     => 'Info',
                'circle-check'    => 'Check',
                'shield-halved'   => 'Shield',
                'lock'            => 'Lock',
                'globe'           => 'Globe',
                'location-dot'    => 'Location',
                'calendar'        => 'Calendar',
                'clock'           => 'Clock',
                'user'            => 'User',
                'users'           => 'Users',
                'building'        => 'Building',
                'briefcase'       => 'Briefcase',
                'graduation-cap'  => 'Education',
                'truck'           => 'Truck',
                'rocket'          => 'Rocket',
            ],
        ];
    }
}