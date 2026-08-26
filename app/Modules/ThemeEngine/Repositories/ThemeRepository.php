<?php

namespace App\Modules\ThemeEngine\Repositories;

use App\Modules\ThemeEngine\Contracts\ThemeInterface;
use App\Modules\ThemeEngine\Exceptions\ThemeNotFoundException;

class ThemeRepository
{
    /** @var array<string, ThemeInterface> */
    protected array $themes = [];

    public function register(ThemeInterface $theme): void
    {
        // Klíčem v poli bude název třídy, tj. "SimpleBlog"
        $this->themes[$theme->getName()] = $theme;
    }

    public function find(string $name): ThemeInterface
    {
        if (! $this->has($name)) {
            throw ThemeNotFoundException::named($name);
        }

        return $this->themes[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->themes[$name]);
    }

    public function all(): array
    {
        return $this->themes;
    }
}


