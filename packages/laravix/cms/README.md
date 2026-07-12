<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/Laravix/laravix/main/public/laravix-logo-white.svg">
    <img src="https://raw.githubusercontent.com/Laravix/laravix/main/public/laravix-logo-black.svg" width="360" alt="Laravix CMS">
  </picture>
</p>

<p align="center">
  <strong>The multi-tenant CMS for the Laravel ecosystem — visual builder, headless API, plugins.</strong>
</p>

<p align="center">
  <a href="https://laravix.com">Website</a> ·
  <a href="https://laravix.com/docs">Documentation</a> ·
  <a href="https://laravix.com/changelog">Changelog</a>
</p>

<p align="center">
  <a href="https://packagist.org/packages/laravix/cms">
    <img src="https://img.shields.io/packagist/v/laravix/cms?style=flat-square&color=6366f1" alt="Latest Version">
  </a>
  <a href="https://packagist.org/packages/laravix/cms">
    <img src="https://img.shields.io/packagist/dt/laravix/cms?style=flat-square&color=6366f1" alt="Total Downloads">
  </a>
  <a href="LICENSE">
    <img src="https://img.shields.io/badge/License-GPL--3.0--or--later-6366f1?style=flat-square" alt="License">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.4">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Filament-5-f59e0b?style=flat-square" alt="Filament 5">
  <img src="https://img.shields.io/badge/Livewire-4-4e56a6?style=flat-square" alt="Livewire 4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Meilisearch-FF5CAA?style=flat-square&logo=meilisearch&logoColor=white" alt="Meilisearch">
</p>

---

Laravix CMS lets you run **any number of websites from a single admin panel**. Each site is a fully isolated tenant with its own content, users, navigation, media, settings, and theme — and each site independently chooses how it's delivered: rendered by a Blade theme, or served headless through a REST API to any frontend you like.

Content is built visually in a **drag-and-drop page builder** with 45+ ready-made blocks, or structured through custom content types and fields when you need more than pages and posts.

## Installation

```bash
composer create-project laravix/laravix my-site
cd my-site
php artisan laravix:install
```

The installer walks you through the database setup, publishes the prebuilt assets and the default theme, and creates your first site and super admin account — optionally with demo content. **No Node.js or npm required.**

Then serve the application and open **/admin**.

## Upgrading

```bash
php artisan laravix:upgrade
```

Runs `composer update laravix/cms`, migrates the database and republishes the assets. The admin panel shows a banner whenever a new release is available.

## Why Laravix?

- **One panel, many sites.** True multi-tenancy is the foundation, not an afterthought. Users get per-site roles, content and settings never leak between tenants, and super admins oversee everything from one place.
- **Visual building without lock-in.** The GrapesJS-powered builder ships with hero sections, pricing tables, galleries, sliders, forms, FAQs, and dozens more blocks — and every block is a PHP class you can extend or replace.
- **Themed or headless, per site.** Ship a classic Blade-themed website today and a Nuxt/Next frontend tomorrow, from the same installation. The `/api/v1` endpoints expose pages, posts, taxonomies, navigation, settings, and search. Access requires an API token — create named tokens per site in **Settings → API** and send them as an `Authorization: Bearer lvx_…` header.
- **Extensible by design.** Registries for blocks, content types, fields, taxonomies, navigation, settings, and routes let plugins hook into every layer — a Laravix plugin is just a Laravel package talking to those registries. laravix.com itself runs on first-party plugins built the same way.
- **Multilingual.** Translatable content with locale-prefixed URLs across 17 content languages, and an admin panel in English, Czech and Slovak — more on the way.

## Feature highlights

| | |
|---|---|
| **Content** | Pages, posts, and archives with drafts, scheduled publishing, full revision history, and custom fields |
| **Builder** | Split-screen visual editor, 45+ blocks, custom code blocks, live preview |
| **Search** | Full-text search backed by Meilisearch via Laravel Scout |
| **SEO** | Per-content metadata, Open Graph, JSON-LD, tenant-aware `sitemap.xml` |
| **Media** | Uploads with automatic image variants and per-site storage |
| **Users** | Email invitations, per-site roles, full audit trail via activity log |
| **Delivery** | Blade themes with a `theme.json` manifest, or headless REST API with token authentication and rate limiting |
| **Housekeeping** | Redirect manager (301/302), hierarchical taxonomies, per-site navigation with live preview |

## Built with

Laravel 13 · Filament 5 · Livewire 4 · Tailwind CSS 4 · GrapesJS · Meilisearch · Pest 4 — running on PHP 8.4.

## Contributing & development

This repository is a **read-only split** of the Laravix development monorepo — issues and feature requests are welcome here, pull requests happen in [MartinKoudela/laravix-cms](https://github.com/MartinKoudela/laravix-cms).

## Status

Laravix is under active development and moving fast. Follow the [changelog](https://laravix.com/changelog) for what's new, and expect some breaking changes before the first stable release.

## License

Laravix CMS is open-source software licensed under the [GNU General Public License v3.0](LICENSE).

Built by [Martin Koudela](https://martinkoudela.com) · [mk@martinkoudela.com](mailto:martin@martinkoudela.com)
