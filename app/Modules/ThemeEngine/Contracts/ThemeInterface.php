<?php

namespace App\Modules\ThemeEngine\Contracts;

interface ThemeInterface
{
    public function getName(): string;
    public function getPath(): string;
    public function getViewsPath(): string;
    public function getAssetsPath(): string;
}

