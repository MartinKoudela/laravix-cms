<?php

namespace App\Support;

use App\Enums\FieldType;
use App\Models\Media;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Storage;

class AppearanceComponentFactory
{
    public static function make(AppearanceDefinition $definition): mixed
    {
        $key = 'appearance_'.$definition->key;

        $component = match ($definition->type) {
            FieldType::TEXT => TextInput::make($key)->label($definition->label),
            FieldType::COLOR => TextInput::make($key)->label($definition->label)->type('color')->extraInputAttributes(['style' => 'height:40px;padding:2px 4px;cursor:pointer']),
            FieldType::TEXTAREA => Textarea::make($key)->label($definition->label)->columnSpanFull(),
            FieldType::RICH_TEXT => RichEditor::make($key)->label($definition->label)->columnSpanFull(),
            FieldType::BOOLEAN => Toggle::make($key)->label($definition->label),
            FieldType::DATE => DatePicker::make($key)->label($definition->label),
            FieldType::DATETIME => DateTimePicker::make($key)->label($definition->label),
            FieldType::NUMBER => TextInput::make($key)->label($definition->label)->numeric(),
            FieldType::URL => TextInput::make($key)->label($definition->label)->url(),
            FieldType::IMAGE, FieldType::FILE => Select::make($key)
                ->label($definition->label)
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
                ),
            FieldType::SELECT => Select::make($key)
                ->label($definition->label)
                ->options($definition->config['options'] ?? []),
        };

        if ($definition->hint) {
            $component->helperText($definition->hint);
        }

        return $component;
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
