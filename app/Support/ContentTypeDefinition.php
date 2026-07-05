<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

class ContentTypeDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $pluralLabel,
        public readonly bool $linkableInNavigation = false,
        public readonly bool $hasBuilder = true,
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder);
    }

    public function pluralLabel(string $pluralLabel): static
    {
        return new static($this->key, $this->label, $pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder);
    }

    public function linkableInNavigation(bool $linkable = true): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $linkable, $this->hasBuilder);
    }

    public function builder(bool $hasBuilder = true): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $this->linkableInNavigation, $hasBuilder);
    }
}
