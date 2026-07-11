<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

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
        public readonly ?string $category = null,
        public readonly \Closure|string|null $canvasHtml = null,
        public readonly array $defaultData = [],
        public readonly ?string $gjsIcon = null,
        public readonly array $contentTypes = [],
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->icon, $this->schema, $this->nestable, $this->category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function icon(string $icon): static
    {
        return new static($this->key, $this->label, $icon, $this->schema, $this->nestable, $this->category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function gjsIcon(string $icon): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $this->nestable, $this->category, $this->canvasHtml, $this->defaultData, $icon, $this->contentTypes);
    }

    public function schema(\Closure|array $schema): static
    {
        return new static($this->key, $this->label, $this->icon, $schema, $this->nestable, $this->category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function nestable(bool $nestable): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $nestable, $this->category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function category(string $category): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $this->nestable, $category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function canvasHtml(\Closure|string $html): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $this->nestable, $this->category, $html, $this->defaultData, $this->gjsIcon, $this->contentTypes);
    }

    public function defaultData(array $data): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $this->nestable, $this->category, $this->canvasHtml, $data, $this->gjsIcon, $this->contentTypes);
    }

    public function contentTypes(array $types): static
    {
        return new static($this->key, $this->label, $this->icon, $this->schema, $this->nestable, $this->category, $this->canvasHtml, $this->defaultData, $this->gjsIcon, $types);
    }

    public function supportsContentType(?string $type): bool
    {
        return $this->contentTypes === [] || in_array($type, $this->contentTypes, true);
    }

    public function resolveCanvasHtml(): string
    {
        if ($this->canvasHtml === null) {
            return '<div style="padding:40px;text-align:center;color:#9ca3af;font-family:sans-serif;">'.__($this->label).'</div>';
        }

        return is_callable($this->canvasHtml) ? ($this->canvasHtml)() : $this->canvasHtml;
    }

    public function toGrapesBlock(): array
    {
        $iconClass = $this->gjsIcon ?? (str_starts_with((string) $this->icon, 'heroicon-') ? null : $this->icon);
        $media = '';
        if ($iconClass) {
            $needsPrefix = ! preg_match('/^fa-(brands|regular|light|thin|duotone)\b/', $iconClass);
            $faClass = $needsPrefix ? 'fa-solid '.$iconClass : $iconClass;
            $media = '<i class="'.$faClass.'" style="font-size:1.5rem;display:block;margin:0 auto 4px;"></i>';
        }

        return [
            'id' => $this->key,
            'label' => __($this->label),
            'category' => $this->category ? __($this->category) : __('laravix::blocks.categories.general'),
            'content' => $this->resolveCanvasHtml(),
            'media' => $media,
        ];
    }

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
                    Section::make(fn () => __('laravix::blocks.settings.title'))
                        ->collapsed()
                        ->schema([
                            TextInput::make('css_class')
                                ->label(fn () => __('laravix::blocks.settings.css_class')),
                            Select::make('padding')
                                ->label(fn () => __('laravix::blocks.settings.padding'))
                                ->options(fn () => [
                                    'none' => __('laravix::blocks.padding.none'),
                                    'sm' => __('laravix::blocks.padding.small'),
                                    'md' => __('laravix::blocks.padding.medium'),
                                    'lg' => __('laravix::blocks.padding.large'),
                                ]),
                            TextInput::make('background_color')
                                ->label(fn () => __('laravix::blocks.settings.background_color'))
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
