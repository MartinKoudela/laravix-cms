<?php

namespace App\Filament\Concerns;

trait TranslatesNavigationGroup
{
    public static function getNavigationGroup(): ?string
    {
        return static::$navigationGroup ? __(static::$navigationGroup) : null;
    }
}
