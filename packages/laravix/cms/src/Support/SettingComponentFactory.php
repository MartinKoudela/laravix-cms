<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\HtmlString;
use Laravix\Cms\Enums\FieldType;

class SettingComponentFactory
{
    public static function make(SettingDefinition $definition): mixed
    {
        $key = $definition->key;

        $label = __($definition->label);

        $component = match ($definition->type) {
            FieldType::TEXT => TextInput::make($key)->label($label),
            FieldType::COLOR => TextInput::make($key)->label($label)->type('color')->extraInputAttributes(['style' => 'height:40px;padding:2px 4px;cursor:pointer']),
            FieldType::TEXTAREA => Textarea::make($key)->label($label)->columnSpanFull(),
            FieldType::RICH_TEXT => RichEditor::make($key)->label($label)->columnSpanFull(),
            FieldType::BOOLEAN => Toggle::make($key)->label($label),
            FieldType::DATE => DatePicker::make($key)->label($label),
            FieldType::DATETIME => DateTimePicker::make($key)->label($label),
            FieldType::NUMBER => TextInput::make($key)->label($label)->numeric(),
            FieldType::URL => TextInput::make($key)->label($label)->url(),
            FieldType::IMAGE, FieldType::FILE => FieldComponentFactory::mediaSelect($key, $label),
            FieldType::SELECT => Select::make($key)
                ->label($label)
                ->options($definition->config['options'] ?? []),
        };

        if ($definition->hint) {
            $component->helperText(__($definition->hint));
        }

        if ($definition->required) {
            $component->required();
        }

        if (isset($definition->config['email']) && $definition->config['email']) {
            $component->email();
        }

        if (isset($definition->config['maxLength'])) {
            $component->maxLength($definition->config['maxLength']);
        }

        if (isset($definition->config['placeholder'])) {
            $component->placeholder($definition->config['placeholder']);
        }

        if ($component instanceof TextInput && isset($definition->config['prefixIcon'])) {
            $component->prefixIcon(static::icon($definition->config['prefixIcon']));
        }

        return $component;
    }

    private static function icon(string $name): string|HtmlString
    {
        $brandClass = NavigationIconRegistry::brandIconClass($name);

        if ($brandClass === null) {
            return $name;
        }

        return new HtmlString(
            '<i class="'.$brandClass.'" style="font-size:1rem;line-height:1;display:flex;align-items:center;justify-content:center;width:100%;height:100%"></i>'
        );
    }
}
