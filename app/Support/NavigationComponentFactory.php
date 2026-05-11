<?php

namespace App\Support;

use App\Models\Content;
use App\Models\Media;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Storage;

class NavigationComponentFactory
{
    public static function make(NavigationDefinition $definition): Repeater
    {
        return Repeater::make('navigations.'.$definition->key)
            ->label(fn () => __($definition->label))
            ->schema([
                TextInput::make('label')
                    ->label(fn () => __('common.label'))
                    ->required()
                    ->live(debounce: 500)
                    ->columnSpanFull(),
                Select::make('content_id')
                    ->label(fn () => __('content.types.page'))
                    ->options(fn () => Content::query()
                        ->where('site_id', filament()->getTenant()?->id)
                        ->whereIn('type', ['page', 'archive'])
                        ->where('status', 'published')
                        ->orderBy('title')
                        ->pluck('title', 'id')
                    )
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (?int $state, Set $set) {
                        if (! $state) {
                            return;
                        }
                        $content = Content::find($state);
                        if ($content) {
                            $set('url', $content->is_homepage ? '/' : '/'.$content->slug);
                        }
                    })
                    ->placeholder(fn () => __('navigation.labels.url_manual'))
                    ->nullable(),
                TextInput::make('url')
                    ->label(fn () => __('common.url'))
                    ->live(debounce: 500)
                    ->nullable(),
                Select::make('target')
                    ->label(fn () => __('navigation.labels.target'))
                    ->options([
                        '_self' => __('navigation.options.same_tab'),
                        '_blank' => __('navigation.options.new_tab'),
                    ])
                    ->default('_self'),
                static::mediaSelect('image_id', __('common.image')),
                Textarea::make('description')
                    ->label(fn () => __('common.description'))
                    ->rows(2)
                    ->columnSpanFull(),
                Repeater::make('children')
                    ->label(fn () => __('navigation.labels.submenu'))
                    ->schema([
                        TextInput::make('label')
                            ->label(fn () => __('common.label'))
                            ->nullable()
                            ->columnSpanFull(),
                        Select::make('content_id')
                            ->label(fn () => __('content.types.page'))
                            ->options(fn () => Content::query()
                                ->where('site_id', filament()->getTenant()?->id)
                                ->whereIn('type', ['page', 'archive'])
                                ->where('status', 'published')
                                ->orderBy('title')
                                ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (?int $state, Set $set) {
                                if (! $state) {
                                    return;
                                }
                                $content = Content::find($state);
                                if ($content) {
                                    $set('url', $content->is_homepage ? '/' : '/'.$content->slug);
                                }
                            })
                            ->placeholder(fn () => __('navigation.labels.url_manual'))
                            ->nullable(),
                        TextInput::make('url')
                            ->label(fn () => __('common.url'))
                            ->nullable(),
                        Select::make('target')
                            ->label(fn () => __('navigation.labels.target'))
                            ->options([
                                '_self' => __('navigation.options.same_tab'),
                                '_blank' => __('navigation.options.new_tab'),
                            ])
                            ->nullable()
                            ->default('_self'),
                        static::mediaSelect('image_id', __('common.image')),
                        Textarea::make('description')
                            ->label(fn () => __('common.description'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->collapsible()
            ->reorderableWithButtons()
            ->columnSpanFull();
    }

    public static function mediaSelect(string $key, string $label): Select
    {
        return Select::make($key)
            ->label($label)
            ->allowHtml()
            ->searchable()
            ->nullable()
            ->getSearchResultsUsing(fn (string $search) => Media::where('site_id', filament()->getTenant()?->id)
                ->where('name', 'like', "%{$search}%")
                ->limit(20)
                ->get()
                ->mapWithKeys(fn (Media $media) => [$media->id => static::mediaOptionLabel($media)])
                ->toArray()
            )
            ->options(fn () => Media::where('site_id', filament()->getTenant()?->id)
                ->limit(20)
                ->get()
                ->mapWithKeys(fn (Media $media) => [$media->id => static::mediaOptionLabel($media)])
                ->toArray()
            )
            ->getOptionLabelUsing(fn ($value) => ($media = Media::find($value))
                ? static::mediaOptionLabel($media)
                : '-'
            );
    }

    private static function mediaOptionLabel(Media $media): string
    {
        $url = e(Storage::disk($media->disk)->url($media->path));
        $name = e($media->name);

        return "<div style=\"display:flex;align-items:center;gap:12px;padding:4px 0\">
            <img src=\"{$url}\" style=\"width:48px;height:48px;object-fit:cover;border-radius:6px;flex-shrink:0\">
            <span style=\"font-weight:500;font-size:14px\">{$name}</span>
        </div>";
    }
}
