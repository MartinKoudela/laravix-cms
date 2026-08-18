<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

class ThemeManifest
{
    private static ?array $cache = null;

    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly ?string $version = null,
        public readonly ?string $author = null,
        public readonly ?string $description = null,
        public readonly ?string $screenshot = null,
    ) {}

    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        self::$cache = [];

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) ?: [] as $path) {
            if ($manifest = self::read(basename($path), $path)) {
                self::$cache[$manifest->key] = $manifest;
            }
        }

        return self::$cache;
    }

    public static function find(string $key): ?self
    {
        return self::all()[$key] ?? null;
    }

    public static function flush(): void
    {
        self::$cache = null;
    }

    public function path(string $append = ''): string
    {
        return base_path("themes/{$this->key}").($append === '' ? '' : '/'.ltrim($append, '/'));
    }

    public function screenshotPath(): ?string
    {
        if ($this->screenshot !== null) {
            return is_file($path = $this->path($this->screenshot)) ? $path : null;
        }

        foreach (['svg', 'webp', 'png', 'jpg'] as $extension) {
            if (is_file($path = $this->path("preview.{$extension}"))) {
                return $path;
            }
        }

        return null;
    }

    public function byline(): ?string
    {
        $parts = array_filter([$this->version, $this->author]);

        return $parts === [] ? null : implode(' · ', $parts);
    }

    private static function read(string $key, string $path): ?self
    {
        if (! is_file($file = $path.'/theme.json')) {
            return null;
        }

        $data = json_decode((string) file_get_contents($file), true);

        if (! is_array($data) || ! is_string($data['name'] ?? null) || trim($data['name']) === '') {
            return null;
        }

        return new self(
            key: $key,
            name: trim($data['name']),
            version: self::string($data, 'version'),
            author: self::string($data, 'author'),
            description: self::string($data, 'description'),
            screenshot: self::string($data, 'screenshot'),
        );
    }

    private static function string(array $data, string $key): ?string
    {
        return is_string($data[$key] ?? null) && trim($data[$key]) !== '' ? trim($data[$key]) : null;
    }
}
