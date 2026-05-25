<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use App\Enums\FieldType;
use App\Models\Media;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Storage;

class FieldComponentFactory
{
    public static function make(FieldDefinition $definition): mixed
    {
        $key = 'field_'.$definition->key;
        $label = __($definition->label);

        return match ($definition->type) {
            FieldType::TEXT => TextInput::make($key)->label($label),
            FieldType::TEXTAREA => Textarea::make($key)->label($label)->columnSpanFull(),
            FieldType::RICH_TEXT => RichEditor::make($key)->label($label)->columnSpanFull(),
            FieldType::BOOLEAN => Toggle::make($key)->label($label),
            FieldType::DATE => DatePicker::make($key)->label($label),
            FieldType::DATETIME => DateTimePicker::make($key)->label($label),
            FieldType::NUMBER => TextInput::make($key)->label($label)->numeric(),
            FieldType::URL => TextInput::make($key)->label($label)->url(),
            FieldType::IMAGE, FieldType::FILE => static::mediaSelect($key, $label),
            FieldType::SELECT => Select::make($key)
                ->label($label)
                ->options($definition->config['options'] ?? []),
        };

        if ($definition->hint) {
            $component->helperText(__($definition->hint));
        }

        return $component;
    }

    public static function mediaSelect(string $key, string $label): Select
    {
        return Select::make($key)
            ->label($label)
            ->allowHtml()
            ->searchable()
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
            )
            ->createOptionForm([
                FileUpload::make('path')
                    ->label(__('common.file'))
                    ->required()
                    ->disk('public')
                    ->directory('media')
                    ->storeFileNamesIn('name')
                    ->acceptedFileTypes(['image/*'])
                    ->maxSize(524288)
                    ->imageEditor()
                    ->columnSpanFull(),
            ])
            ->createOptionUsing(function (array $data): int {
                $filePath = Storage::disk('public')->path($data['path']);

                $media = Media::create([
                    'site_id' => filament()->getTenant()?->id,
                    'path' => $data['path'],
                    'name' => $data['name'] ?? basename($data['path']),
                    'disk' => 'public',
                    'mime_type' => mime_content_type($filePath) ?: 'application/octet-stream',
                    'size' => filesize($filePath) ?: 0,
                    'created_by' => auth()->id(),
                ]);

                return $media->id;
            });
    }

    public static function mediaOptionLabel(Media $media): string
    {
        $url = e(Storage::disk($media->disk)->url($media->path));
        $name = e($media->name);

        return "<div style=\"display:flex;align-items:center;gap:12px;padding:4px 0\">
            <img src=\"{$url}\" style=\"width:48px;height:48px;object-fit:cover;border-radius:6px;flex-shrink:0\">
            <span style=\"font-weight:500;font-size:14px\">{$name}</span>
        </div>";
    }
}
