<?php

namespace App\Enums;

enum ImageVariant: string
{
    case THUMBNAIL = 'thumbnail'; // 300×300
    case MEDIUM = 'medium';    // 800×600
    case LARGE = 'large';     // 1200×800
    case OG = 'og';        // 1200×630
    case FAVICON = 'favicon';   // 32×32
    case FULL = 'full';      // original

    public function width(): ?int
    {
        return match ($this) {
            self::THUMBNAIL => 300,
            self::MEDIUM => 800,
            self::LARGE,
            self::OG => 1200,
            self::FAVICON => 32,
            self::FULL => null,
        };
    }

    public function height(): ?int
    {
        return match ($this) {
            self::THUMBNAIL => 300,
            self::MEDIUM => 600,
            self::LARGE => 800,
            self::OG => 630,
            self::FAVICON => 32,
            self::FULL => null,
        };
    }
}
