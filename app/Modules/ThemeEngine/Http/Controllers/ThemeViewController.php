<?php

namespace App\Modules\ThemeEngine\Http\Controllers;

use App\Modules\ThemeEngine\Facades\Theme;
use App\Modules\ThemeEngine\Services\UrlResolver;
use Illuminate\Support\Str;
use Laravix\Cms\Http\Controllers\Controller;

class ThemeViewController extends Controller
{
    public function handle(?string $fallbackPlaceholder = null, UrlResolver $resolver = null)
    {
        Theme::set('SimpleBlog');

        $resolver = $resolver ?? app(UrlResolver::class);
        $routeData = $resolver->resolve(request()->path());

        $entity = $routeData['entity'];
        $slug = $routeData['slug'];

        // ARCHIV / INDEX (např. /kategorie/sport, /clanky, /produkty)
        if ($routeData['action'] === 'index') {
            return Theme::renderIndex($entity, [
                'entity' => $entity,
                'slug' => $slug,
                'title' => 'Archiv: ' . ucfirst($entity),
                'posts' => $this->getDummyCollection($entity),
            ]);
        }

        // DETAIL / SHOW (např. /, /ahoj, /clanky/detail-clanku)
        return Theme::renderShow($entity, [
            'entity' => $entity,
            'slug' => $slug,
            'title' => ucfirst($entity) . ' - ' . Str::headline($slug),
            'post' => $this->getDummyModel($entity, $slug),
        ]);
    }

    /**
     * Vygeneruje kompletní dummy model se všemi očekávanými vlastnostmi.
     */
    private function getDummyModel(string $entity, ?string $slug): object
    {
        return (object)[
            'id' => 1,
            'title' => 'Ukázkový název (' . ucfirst($entity) . ': ' . ($slug ?? 'home') . ')',
            'slug' => $slug ?? 'home',
            'excerpt' => 'Toto je stručný výtah z obsahu pro ukázku v šabloně.',
            'content' => '<p>Vítejte! Toto je plný obsah generovaný z controlleru. Zde může být libovolné HTML.</p>',
            'created_at' => now()->format('d. m. Y'),
            'updated_at' => now()->format('d. m. Y H:i'),
            'author' => (object)['name' => 'Admin'],
        ];
    }

    /**
     * Vygeneruje kolekci dummy objektů pro výpisy a archivy.
     */
    private function getDummyCollection(string $entity, int $count = 5): array
    {
        $items = [];
        for ($i = 1; $i <= $count; $i++) {
            $items[] = (object)[
                'id' => $i,
                'title' => ucfirst($entity) . " článek č. {$i}",
                'slug' => "{$entity}-clanek-{$i}",
                'excerpt' => "Stručný popis pro článek číslo {$i}...",
                'content' => "<p>Obsah článku číslo {$i}...</p>",
                'created_at' => now()->subDays($i)->format('d. m. Y'),
                'author' => (object)['name' => 'Redaktor'],
            ];
        }

        return $items;
    }
}



