<?php
namespace App\Modules\ThemeEngine;

use App\Modules\ThemeEngine\Contracts\ThemeInterface;
use ReflectionClass;

abstract class AbstractTheme implements ThemeInterface
{
    public function getName(): string
    {
        return class_basename(static::class); // Vrací "SimpleBlog"
    }

    public function getPath(): string
    {
        $reflector = new ReflectionClass(static::class);
        return dirname($reflector->getFileName());
    }

    public function getViewsPath(): string
    {
        return $this->getPath() . '/resources/views';
    }

    abstract public function getAssetsPath(): string;
}
