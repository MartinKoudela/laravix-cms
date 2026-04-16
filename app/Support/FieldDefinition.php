<?php

namespace App\Support;

use App\Enums\FieldType;

class FieldDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly FieldType $type,
        public readonly string $label,
        public readonly ?string $group = null,
        public readonly array $config = [],
    ) {}

    public static function make(string $key): static
    {
        return new static($key, FieldType::TEXT, $key);
    }

    public function type(FieldType $type): static
    {
        return new static($this->key, $type, $this->label,
            $this->group, $this->config);
    }

    public function label(string $label): static
    {
        return new static($this->key, $this->type, $label,
            $this->group, $this->config);
    }

    public function group(string $group): static
    {
        return new static($this->key, $this->type,
            $this->label, $group, $this->config);
    }

    public function config(array $config): static
    {
        return new static($this->key, $this->type,
            $this->label, $this->group, $config);
    }
}
