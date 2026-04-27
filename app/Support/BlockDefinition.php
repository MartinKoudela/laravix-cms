<?php

namespace App\Support;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class BlockDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly ?string $icon = null,
        public readonly \Closure|array $schema = [],
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->icon, $this->schema);
    }

    public function icon(string $icon): static
    {
        return new static($this->key, $this->label, $icon, $this->schema);
    }

    public function schema(\Closure|array $schema): static
    {
        return new static($this->key, $this->label, $this->icon, $schema);
    }

    public function toBlock(): Block
    {
        $schema = is_callable($this->schema) ? ($this->schema)() : $this->schema;

        $block = Block::make($this->key)
            ->label(fn () => __($this->label))
            ->schema([
                ...$schema,
                Section::make(__('Block Settings'))
                    ->collapsed()
                    ->schema([
                        TextInput::make('css_class')
                            ->label(__('CSS Class')),
                        Select::make('padding')
                            ->label(__('Padding'))
                            ->options([
                                'none' => __('None'),
                                'sm' => __('Small'),
                                'md' => __('Medium'),
                                'lg' => __('Large'),
                            ]),
                        TextInput::make('background_color')
                            ->label(__('Background Color'))
                            ->type('color'),
                    ]),
            ]);

        if ($this->icon) {
            $block->icon($this->icon);
        }

        return $block;
    }
}
