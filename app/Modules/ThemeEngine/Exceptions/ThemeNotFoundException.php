<?php

namespace App\Modules\ThemeEngine\Exceptions;

use Exception;

class ThemeNotFoundException extends Exception
{
    public static function named(string $themeName): self
    {
        return new self("The theme named '{$themeName}' was not found or is not registered.");
    }
}
