<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

class ContentTypeDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $pluralLabel,
        public readonly bool $linkableInNavigation = false,
        public readonly bool $hasBuilder = true,
        public readonly ?string $routePrefix = null,
        public readonly array $taxonomyTypes = [],
    ) {}

    public static function make(string $key): static
    {
        return new static($key, $key, $key);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder, $this->routePrefix, $this->taxonomyTypes);
    }

    public function pluralLabel(string $pluralLabel): static
    {
        return new static($this->key, $this->label, $pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder, $this->routePrefix, $this->taxonomyTypes);
    }

    public function linkableInNavigation(bool $linkable = true): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $linkable, $this->hasBuilder, $this->routePrefix, $this->taxonomyTypes);
    }

    public function builder(bool $hasBuilder = true): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $this->linkableInNavigation, $hasBuilder, $this->routePrefix, $this->taxonomyTypes);
    }

    public function routePrefix(?string $routePrefix): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder, $routePrefix, $this->taxonomyTypes);
    }

    public function taxonomyTypes(array $taxonomyTypes): static
    {
        return new static($this->key, $this->label, $this->pluralLabel,
            $this->linkableInNavigation, $this->hasBuilder, $this->routePrefix, $taxonomyTypes);
    }
}
