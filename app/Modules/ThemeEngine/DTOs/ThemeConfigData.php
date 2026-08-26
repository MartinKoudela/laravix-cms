<?php

namespace App\Modules\ThemeEngine\DTOs;

readonly class ThemeConfigData
{
    public function __construct(
        public string $name,
        public string $author,
        public string $version,
        public array $options = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? 'Unknown',
            author: $data['author'] ?? 'Anonymous',
            version: $data['version'] ?? '1.0.0',
            options: $data['options'] ?? []
        );
    }
}
