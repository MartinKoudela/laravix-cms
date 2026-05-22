<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlockDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly ?string $icon = null,
        public readonly \Closure|array $schema = [],
        public readonly bool $nestable = true,
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->icon, $this->schema, $this->nestable);
    }

    public function icon(string $icon): static
    {
        return new static($this->key, $this->label, $icon, $this->schema, $this->nestable);
    }

    public function schema(\Closure|array $schema): static
    {
        return new static($this->key, $this->label, $this->icon, $schema, $this->nestable);
    }

    public function nestable(bool $nestable): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $nestable);
    }

    /** @return array<int, array{key: string, label: string, type: string, options: array, fields?: array}> */
    public function toEditorFields(): array
    {
        $schema = is_callable($this->schema) ? ($this->schema)() : $this->schema;
        $fields = [];

        foreach ($schema as $component) {
            if ($component instanceof Builder) {
                continue;
            }

            if ($component instanceof Repeater) {
                $fields[] = $this->repeaterToField($component);

                continue;
            }

            if (! $component instanceof Field) {
                continue;
            }

            $fields[] = $this->fieldToMeta($component);
        }

        return $fields;
    }

    /** @return array{key: string, label: string, type: string, options: array} */
    private function fieldToMeta(Field $component): array
    {
        try {
            $ref = new \ReflectionObject($component);
            $prop = $ref->getProperty('label');
            $raw = $prop->getValue($component);
            $label = $raw instanceof \Closure ? $raw() : ($raw ?? ucfirst($component->getName()));
            $label = is_string($label) ? $label : ucfirst($component->getName());
        } catch (\Throwable) {
            $label = ucfirst($component->getName());
        }

        $type = 'text';
        $options = [];

        if ($component instanceof RichEditor) {
            $type = 'richtext';
        } elseif ($component instanceof Textarea) {
            $type = 'textarea';
        } elseif ($component instanceof Select) {
            if (str_ends_with($component->getName(), '_id')) {
                $type = 'image';
            } else {
                $type = 'select';
                try {
                    $opts = $component->getOptions();
                    $options = is_callable($opts) ? $opts() : ($opts ?? []);
                } catch (\Throwable) {
                    $options = [];
                }
            }
        }

        return [
            'key' => $component->getName(),
            'label' => (string) $label,
            'type' => $type,
            'options' => $options,
        ];
    }

    /** @return array{key: string, label: string, type: 'repeater', options: array, fields: array} */
    private function repeaterToField(Repeater $component): array
    {
        try {
            $label = $component->getLabel();
            $label = is_callable($label) ? $label() : ($label ?? ucfirst($component->getName()));
        } catch (\Throwable) {
            $label = ucfirst($component->getName());
        }

        $subFields = [];

        try {
            $children = $component->getDefaultChildComponents();

            if ($children instanceof Schema) {
                $children = $children->getComponents();
            }
        } catch (\Throwable) {
            $children = [];
        }

        foreach ($children as $child) {
            if (! $child instanceof Field) {
                continue;
            }
            try {
                $subFields[] = $this->fieldToMeta($child);
            } catch (\Throwable) {
                $subFields[] = [
                    'key' => $child->getName(),
                    'label' => ucfirst($child->getName()),
                    'type' => 'text',
                    'options' => [],
                ];
            }
        }

        return [
            'key' => $component->getName(),
            'label' => (string) $label,
            'type' => 'repeater',
            'options' => [],
            'fields' => $subFields,
        ];
    }

    public function toBlock(): Block
    {
        $definition = $this;

        $block = Block::make($this->key)
            ->label(fn () => __($definition->label))
            ->schema(function () use ($definition) {
                $schema = is_callable($definition->schema) ? ($definition->schema)() : $definition->schema;

                return [
                    ...$schema,
                    Section::make(fn () => __('blocks.settings.title'))
                        ->collapsed()
                        ->schema([
                            TextInput::make('css_class')
                                ->label(fn () => __('blocks.settings.css_class')),
                            Select::make('padding')
                                ->label(fn () => __('blocks.settings.padding'))
                                ->options(fn () => [
                                    'none' => __('blocks.padding.none'),
                                    'sm' => __('blocks.padding.small'),
                                    'md' => __('blocks.padding.medium'),
                                    'lg' => __('blocks.padding.large'),
                                ]),
                            TextInput::make('background_color')
                                ->label(fn () => __('blocks.settings.background_color'))
                                ->type('color'),
                        ]),
                ];
            });

        if ($this->icon) {
            $block->icon($this->icon);
        }

        return $block;
    }
}
