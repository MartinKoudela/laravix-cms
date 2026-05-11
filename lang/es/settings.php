<?php

return [
    'singular' => 'ajuste',
    'plural' => 'ajustes',
    'tabs' => [
        'general' => 'General',
        'seo' => 'SEO',
        'social' => 'Redes sociales',
    ],
    'fields' => [
        'site_name' => 'Nombre del sitio',
        'site_description' => 'Descripción del sitio',
        'site_logo' => 'Logo del sitio',
        'favicon' => 'Favicon',
        'locale' => 'Idioma',
        'contact_email' => 'Correo de contacto',
        'meta_title' => 'Título meta',
        'meta_description' => 'Descripción meta',
        'og_image' => 'Imagen OG',
        'google_verification' => 'Verificación de Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Guardar ajustes',
        'manage' => 'Gestionar ajustes',
    ],
    'messages' => [
        'saved' => 'Ajustes guardados',
    ],
    'hints' => [
        'logo' => 'Mostrado en el encabezado y pie de página.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Icono de la pestaña del navegador. Recomendado: PNG 32×32 o 64×64.',
        'contact_email' => 'Se usa en formularios de contacto y correos transaccionales.',
        'meta_title' => 'Título de página por defecto cuando no hay título a nivel de contenido.',
        'meta_description' => 'Descripción meta predeterminada (hasta 160 caracteres).',
        'og_image' => 'Imagen Open Graph predeterminada para compartir en redes sociales.',
        'locale' => 'Código de idioma usado en <html lang="">. Ej. en, es, de, fr.',
        'google_verification' => 'Pega el valor del atributo content de la etiqueta meta de Google Search Console.',
        'robots_txt' => 'Dejar vacío para el valor predeterminado (Permitir todo). La URL del sitemap siempre se añade automáticamente.',
    ],
];
