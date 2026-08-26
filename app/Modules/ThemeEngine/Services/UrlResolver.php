<?php

namespace App\Modules\ThemeEngine\Services;

class UrlResolver
{
    public function resolve(string $path): array
    {
        $path = trim($path, '/');

        // 1. Kořenová stránka "/"
        if (empty($path)) {
            return [
                'type' => 'page',
                'entity' => 'page',
                'action' => 'show',
                'slug' => config('permalinks.home_page_id'),
            ];
        }

        $segments = explode('/', $path);
        $firstSegment = $segments[0];

        // 2. Taxonomie / Kategorie s prefixem (např. /kategorie/sport)
        $categoryPrefix = config('permalinks.prefixes.category', 'category');
        if ($firstSegment === $categoryPrefix && isset($segments[1])) {
            return [
                'type' => 'taxonomy',
                'entity' => 'category',
                'action' => 'index',
                'slug' => $segments[1],
            ];
        }

        // 3. Vlastní entita s prefixem (např. /obchod/moje-tricko -> product)
        $entityPrefixes = array_flip(config('permalinks.entities', []));
        if (isset($entityPrefixes[$firstSegment]) && isset($segments[1])) {
            return [
                'type' => 'entity',
                'entity' => $entityPrefixes[$firstSegment], // vráti 'product'
                'action' => 'show',
                'slug' => $segments[1],
            ];
        }

        // 4. Zanořené stránky / Hierarchie (/parent-a/child-1/child-2)
        // Poslední segment je slug hledané stránky, předchozí tvoří rodiče
        $targetSlug = end($segments);

        return [
            'type' => 'page',
            'entity' => 'page',
            'action' => 'show',
            'slug' => $targetSlug,
            'ancestors' => array_slice($segments, 0, -1),
        ];
    }
}
