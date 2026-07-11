<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Database\Seeders;

use Illuminate\Database\Seeder;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;

class DemoSeeder extends Seeder
{
    public function run(?Site $site = null, ?User $author = null): void
    {
        $site ??= Site::firstOrFail();
        $author ??= User::where('is_super_admin', true)->firstOrFail();

        $news = Taxonomy::create([
            'site_id' => $site->id,
            'type' => 'category',
            'name' => 'News',
            'slug' => 'news',
        ]);

        $guides = Taxonomy::create([
            'site_id' => $site->id,
            'type' => 'category',
            'name' => 'Guides',
            'slug' => 'guides',
        ]);

        Content::create([
            'site_id' => $site->id,
            'created_by' => $author->id,
            'type' => 'page',
            'title' => 'Home',
            'slug' => '/',
            'is_homepage' => true,
            'status' => ContentStatus::PUBLISHED,
            'locale' => $site->defaultLocale(),
            'blocks' => [
                [
                    'type' => 'hero',
                    'data' => [
                        'heading' => 'Welcome to '.$site->name,
                        'subheading' => 'This site runs on Laravix CMS. Log into the admin panel to make it yours.',
                        'buttons' => [
                            ['label' => 'Read the blog', 'href' => '/blog', 'variant' => 'primary'],
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'data' => [
                        'heading' => 'Edit this page',
                        'content' => '<p>Open the admin panel, head to <strong>Contents</strong> and edit the Home page — or rebuild it visually with the builder.</p>',
                    ],
                ],
            ],
        ]);

        Content::create([
            'site_id' => $site->id,
            'created_by' => $author->id,
            'type' => 'page',
            'title' => 'About',
            'slug' => 'about',
            'status' => ContentStatus::PUBLISHED,
            'locale' => $site->defaultLocale(),
            'blocks' => [
                [
                    'type' => 'text',
                    'data' => [
                        'heading' => 'About us',
                        'content' => '<p>This is a demo page created by the Laravix installer. Replace this text with your own story.</p>',
                    ],
                ],
            ],
        ]);

        Content::create([
            'site_id' => $site->id,
            'created_by' => $author->id,
            'type' => 'archive',
            'title' => 'Blog',
            'slug' => 'blog',
            'status' => ContentStatus::PUBLISHED,
            'locale' => $site->defaultLocale(),
            'blocks' => [
                [
                    'type' => 'text',
                    'data' => ['heading' => 'Blog'],
                ],
            ],
        ]);

        collect([
            ['title' => 'Hello world', 'slug' => 'hello-world', 'category' => $news, 'body' => '<p>Your first post. Written by the Laravix installer so the blog is not empty.</p>'],
            ['title' => 'Getting started with Laravix', 'slug' => 'getting-started', 'category' => $guides, 'body' => '<p>Create content, manage media, publish — everything lives in the admin panel.</p>'],
            ['title' => 'Building pages visually', 'slug' => 'building-pages-visually', 'category' => $guides, 'body' => '<p>Every page can be composed from blocks or designed in the visual builder.</p>'],
        ])->each(function (array $post) use ($site, $author) {
            $content = Content::create([
                'site_id' => $site->id,
                'created_by' => $author->id,
                'type' => 'post',
                'title' => $post['title'],
                'slug' => $post['slug'],
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'locale' => $site->defaultLocale(),
                'blocks' => [
                    ['type' => 'text', 'data' => ['content' => $post['body']]],
                ],
            ]);

            $content->taxonomies()->attach($post['category']);
        });
    }
}
