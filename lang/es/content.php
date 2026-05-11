<?php

return [
    'plural' => 'contenidos',
    'singular' => 'contenido',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publicación',
        'taxonomies' => 'Taxonomías',
        'builder' => 'Constructor de páginas',
    ],
    'fields' => [
        'meta_title' => 'Título meta',
        'meta_description' => 'Descripción meta',
        'og_image' => 'Imagen OG',
        'body' => 'Cuerpo',
        'hero_image' => 'Imagen principal',
        'excerpt' => 'Extracto',
        'noindex' => 'Ocultar de los motores de búsqueda',
    ],
    'stats' => [
        'recent' => 'Contenido reciente',
        'published' => 'Publicado',
        'published_description' => 'Páginas y publicaciones publicadas',
        'drafts' => 'Borradores',
        'awaiting' => 'Pendiente de publicación',
    ],
    'messages' => [
        'set_as_homepage' => 'Establecer como página de inicio',
        'only_one_homepage' => 'Solo un contenido por sitio puede ser la página de inicio.',
    ],
    'types' => [
        'page' => 'Página',
        'post' => 'Publicación',
        'archive' => 'Archivo',
    ],
    'hints' => [
        'meta_title' => 'Anula el valor predeterminado del sitio cuando se establece.',
        'meta_description' => 'Hasta 160 caracteres. Anula el valor predeterminado del sitio.',
        'og_image' => 'Anula la imagen OG del sitio para esta página.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Revertir',
    ],
];
