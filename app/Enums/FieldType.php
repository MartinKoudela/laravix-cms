<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RICH_TEXT = 'richtext';
    case IMAGE = 'image';
    case URL = 'url';
    case BOOLEAN = 'boolean';
    case COLOR = 'color';
    case DATE = 'date';

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Text',
            self::TEXTAREA => 'Textarea',
            self::RICH_TEXT => 'Rich text (HTML)',
            self::IMAGE => 'Image',
            self::URL => 'URL',
            self::BOOLEAN => 'Boolean (Yes/No)',
            self::COLOR => 'Color',
            self::DATE => 'Date',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }
}
