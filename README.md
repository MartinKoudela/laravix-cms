<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="public/laravix-logo-white.svg">
    <img src="public/laravix-logo-black.svg" width="360" alt="Laravix CMS">
  </picture>
</p>

<p align="center">
  <strong>Multi-tenant CMS built on Laravel and Filament</strong>
</p>

<p align="center">
  <a href="https://github.com/laravix/laravix-cms/commits/main">
    <img src="https://img.shields.io/github/last-commit/laravix/laravix-cms?style=flat-square&color=6366f1" alt="Last Commit">
  </a>
  <a href="https://github.com/laravix/laravix-cms/commits/main">
    <img src="https://img.shields.io/github/commit-activity/m/laravix/laravix-cms?style=flat-square&color=6366f1" alt="Commit Activity">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/License-AGPL_3.0-6366f1?style=flat-square" alt="License">
  </a>
  <a href="https://laravix.com">
    <img src="https://img.shields.io/badge/Website-laravix.com-6366f1?style=flat-square" alt="laravix.com">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Filament-5-f59e0b?style=flat-square" alt="Filament 5">
  <img src="https://img.shields.io/badge/Livewire-4-4e56a6?style=flat-square" alt="Livewire 4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Vite-8-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite 8">
  <img src="https://img.shields.io/badge/MySQL-8.4-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL 8.4">
  <img src="https://img.shields.io/badge/Tested_with-Pest_4-CA3933?style=flat-square" alt="Pest 4">
</p>

---

## Overview

Laravix CMS is a **multi-tenant** content management system. Each site is a fully isolated tenant with its own content, users, roles, navigation, settings, and theme — all managed from a single Filament admin panel.

**Core capabilities:**

- Multi-site tenancy with per-site roles
- Visual block editor with live preview
- Hierarchical taxonomies, media management, redirects
- Email-based user invitations
- Content scheduling, revisions, and activity logging
- Auto-generated sitemaps and robots.txt
- Themeable frontend with Blade

---

## Requirements

| Dependency | Version |
|------------|---------|
| PHP        | 8.4+    |
| MySQL      | 8.4+    |
| Node.js    | 20+     |
| Composer   | 2+      |
| Docker     | Latest  |

---

## Installation

```bash
git clone https://github.com/laravix/laravix-cms.git
cd laravix-cms

composer install
cp .env.example .env

vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail artisan migrate --seed
vendor/bin/sail npm install
vendor/bin/sail npm run build
```

The admin panel is at **http://localhost/admin**.

### Default credentials

| Role    | Email               | Password   |
|---------|---------------------|------------|
| Admin   | admin@example.com   | `example_` |
| Manager | manager@example.com | `example_` |
| User    | user@example.com    | `example_` |

---

## Development

Start all services (server, queue worker, log tail, Vite HMR) with a single command:

```bash
vendor/bin/sail up -d
vendor/bin/sail composer run dev
```

| Service | URL                        |
|---------|----------------------------|
| App     | http://localhost           |
| Admin   | http://localhost/admin     |
| Mailpit | http://localhost:8025      |

---

## Architecture

```
app/
├── Blocks/          # Block definitions (Hero, Text, Cards, …)
├── Console/         # Artisan commands
├── Enums/           # ContentStatus, FieldType, SiteRole, …
├── Filament/        # Admin panel resources, pages, widgets
├── Http/            # Controllers, middleware
├── Livewire/        # Interactive components (BlockEditor)
├── Mail/            # Mailable classes
├── Models/          # Eloquent models
├── Policies/        # Authorization policies
├── Services/        # ContentResolver, SeoBuilder, SiteResolver, …
└── Support/         # Shared helpers

themes/              # Frontend themes (Blade views + theme.json)
```

---

## Features

### Multi-tenancy

Each **Site** is a fully isolated tenant. Users are assigned per-site roles:

| Role      | Description                              |
|-----------|------------------------------------------|
| `admin`   | Full access to site content and settings |
| `manager` | Manage content, no settings access       |
| `user`    | Read-only access                         |

Super admins have access across all sites.

### Content

| Property   | Options                                        |
|------------|------------------------------------------------|
| Type       | `page`, `post`, `archive`                      |
| Status     | `draft`, `published`, `scheduled`, `archived`  |

- Scheduled publishing via `cms:publish-scheduled` (registered in scheduler)
- Full revision history via [promethys/revive](https://github.com/promethys/revive)
- Custom fields per content record
- SEO metadata, JSON-LD, Open Graph support

### Block Editor

Visual block-based content builder with real-time split-screen preview.

| Block         | Description                         |
|---------------|-------------------------------------|
| `Hero`        | Full-width hero with heading and CTA |
| `Text`        | Rich text content                   |
| `Cards`       | Card grid                           |
| `Columns`     | Multi-column layout                 |
| `Button`      | CTA button                          |
| `ButtonGroup` | Row of multiple buttons             |
| `Divider`     | Horizontal separator                |

### Other Features

| Feature             | Description                                                        |
|---------------------|--------------------------------------------------------------------|
| Media               | File upload, hero images, block media, disk-based storage          |
| Navigation          | Header and footer nav per site with live admin preview             |
| Taxonomies          | Hierarchical, site-scoped, with unique slug enforcement            |
| Redirects           | 301/302 redirect rules per site                                    |
| User Invitations    | Email invitation with role assignment                              |
| Activity Log        | Full audit trail (Spatie Activity Log) scoped per site             |
| Sitemap & Robots    | Auto-generated, tenant-aware `sitemap.xml` and `robots.txt`       |

---

## Themes

Themes live in `/themes/{name}/` and consist of Blade views and a `theme.json` manifest. View namespaces are auto-registered as `themes.{name}::*`.

```
themes/default/
├── theme.json
└── views/
    ├── layouts/app.blade.php
    ├── default.blade.php
    ├── page/show.blade.php
    ├── post/show.blade.php
    ├── archive/show.blade.php
    └── blocks/
        ├── hero.blade.php
        ├── text.blade.php
        └── …
```

To create a new theme, duplicate an existing theme folder and update `theme.json`:

```json
{
    "name": "My Theme",
    "description": "A custom Laravix theme"
}
```

---

## Custom Blocks

Create a block class in `app/Blocks/`:

```php
class QuoteBlock extends Block
{
    public static function getName(): string
    {
        return 'quote';
    }

    public static function getSchema(): array
    {
        return [
            Textarea::make('text')->required(),
            TextInput::make('author'),
        ];
    }
}
```

Register it in `AppServiceProvider`:

```php
BlockRegistry::register(QuoteBlock::class);
```

Add the Blade partial in your theme at `themes/{name}/views/blocks/quote.blade.php`.

---

## Testing

```bash
# Run the full test suite
vendor/bin/sail artisan test --compact

# Filter by test name
vendor/bin/sail artisan test --compact --filter=SiteScopingTest
```

Tests are written in [Pest 4](https://pestphp.com). New features should be covered by feature tests in `tests/Feature/`.

---

## Code Style

```bash
vendor/bin/sail bin pint --dirty
```

[Laravel Pint](https://laravel.com/docs/pint) is configured and enforced. Run it before every commit.

---

## Environment Variables

Key variables beyond standard Laravel defaults:

| Variable       | Description                       | Default         |
|----------------|-----------------------------------|-----------------|
| `APP_VERSION`  | Version shown in the admin footer | `1.0.0`         |
| `APP_TIMEZONE` | Default application timezone      | `Europe/Prague` |

---

## Author

**Martin Koudela** — [martinkoudela.com](https://martinkoudela.com) · [martin@martinkoudela.com](mailto:martin@martinkoudela.com)

---

## License

Laravix CMS is open-source software licensed under the [GNU Affero General Public License v3.0](LICENSE).