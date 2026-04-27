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

    public function toBlock(): Block
    {
        $definition = $this;

        $block = Block::make($this->key)
            ->label(fn () => __($definition->label))
            ->schema(function () use ($definition) {
                $schema = is_callable($definition->schema) ? ($definition->schema)() : $definition->schema;

                return [
                    ...$schema,
                    Section::make(fn () => __('Block Settings'))
                        ->collapsed()
                        ->schema([
                            TextInput::make('css_class')
                                ->label(fn () => __('CSS Class')),
                            Select::make('padding')
                                ->label(fn () => __('Padding'))
                                ->options(fn () => [
                                    'none' => __('None'),
                                    'sm' => __('Small'),
                                    'md' => __('Medium'),
                                    'lg' => __('Large'),
                                ]),
                            TextInput::make('background_color')
                                ->label(fn () => __('Background Color'))
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
