<?php

return [
    'singular' => 'configuração',
    'plural' => 'configurações',
    'tabs' => [
        'general' => 'Geral',
        'seo' => 'SEO',
        'social' => 'Redes sociais',
    ],
    'fields' => [
        'site_name' => 'Nome do site',
        'site_description' => 'Descrição do site',
        'site_logo' => 'Logo do site',
        'favicon' => 'Favicon',
        'locale' => 'Idioma',
        'contact_email' => 'E-mail de contato',
        'meta_title' => 'Meta título',
        'meta_description' => 'Meta descrição',
        'og_image' => 'Imagem OG',
        'google_verification' => 'Verificação do Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Salvar configurações',
        'manage' => 'Gerenciar configurações',
    ],
    'messages' => [
        'saved' => 'Configurações salvas',
    ],
    'hints' => [
        'logo' => 'Exibido no cabeçalho e rodapé.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Ícone da aba do navegador. Recomendado: PNG 32×32 ou 64×64.',
        'contact_email' => 'Usado em formulários de contato e e-mails transacionais.',
        'meta_title' => 'Título de página padrão quando nenhum título de conteúdo é definido.',
        'meta_description' => 'Meta descrição padrão (até 160 caracteres).',
        'og_image' => 'Imagem Open Graph padrão para compartilhamento social.',
        'locale' => 'Código de idioma usado em <html lang="">. Ex. en, pt, de, fr.',
        'google_verification' => 'Cole o valor do atributo content da meta tag do Google Search Console.',
        'robots_txt' => 'Deixe vazio para o padrão (Permitir tudo). A URL do sitemap é sempre adicionada automaticamente.',
    ],
];
