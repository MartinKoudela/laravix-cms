<?php

return [
    'plural' => 'conteúdos',
    'singular' => 'conteúdo',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publicação',
        'taxonomies' => 'Taxonomias',
        'builder' => 'Construtor de páginas',
    ],
    'fields' => [
        'meta_title' => 'Meta título',
        'meta_description' => 'Meta descrição',
        'og_image' => 'Imagem OG',
        'body' => 'Corpo',
        'hero_image' => 'Imagem principal',
        'excerpt' => 'Trecho',
        'noindex' => 'Ocultar dos mecanismos de busca',
    ],
    'stats' => [
        'recent' => 'Conteúdo recente',
        'published' => 'Publicado',
        'published_description' => 'Páginas e posts publicados',
        'drafts' => 'Rascunhos',
        'awaiting' => 'Aguardando publicação',
    ],
    'messages' => [
        'set_as_homepage' => 'Definir como página inicial',
        'only_one_homepage' => 'Apenas um conteúdo por site pode ser a página inicial.',
        'save_first_for_builder' => 'Salve o conteúdo primeiro para usar o construtor de blocos.',
        'builder_has_content' => 'A página tem conteúdo salvo do builder.',
        'builder_no_content' => 'A página ainda não tem conteúdo do builder.',
    ],
    'types' => [
        'page' => 'Página',
        'post' => 'Post',
        'archive' => 'Arquivo',
    ],
    'hints' => [
        'meta_title' => 'Substitui o padrão do site quando definido.',
        'meta_description' => 'Até 160 caracteres. Substitui o padrão do site.',
        'og_image' => 'Substitui a imagem OG padrão do site para esta página.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Reverter',
        'open_builder' => 'Abrir Builder',
    ],
];
