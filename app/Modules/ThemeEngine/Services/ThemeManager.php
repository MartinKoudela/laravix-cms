<?php

namespace App\Modules\ThemeEngine\Services;
namespace App\Modules\ThemeEngine\Services;

use App\Modules\ThemeEngine\Contracts\ThemeInterface;
use App\Modules\ThemeEngine\Repositories\ThemeRepository;
use Illuminate\Support\Facades\View;

class ThemeManager
{
    protected ?ThemeInterface $activeTheme = null;

    public function __construct(
        protected ThemeRepository $repository
    ) {}

    public function set(string $name): void
    {
        $this->activeTheme = $this->repository->find($name);
    }

    public function current(): ?ThemeInterface
    {
        return $this->activeTheme;
    }

    public function renderIndex(string $entity, array $data = [])
    {
        $name = $this->activeTheme?->getName();

        return View::first([
            "{$name}::entities.{$entity}.index",
            "{$name}::index",
        ], $data);
    }

    public function renderShow(string $entity, array $data = [])
    {
        $name = $this->activeTheme?->getName();

        return View::first([
            "{$name}::entities.{$entity}.show",
            "{$name}::show",
        ], $data);
    }
}
