<?php

namespace App\Support;

class NavigationDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label);
    }
}
