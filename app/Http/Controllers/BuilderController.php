<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\CustomCodeBlock;
use App\Models\Media;
use App\Models\Site;
use App\Support\BlockRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BuilderController extends Controller
{
    public function edit(Site $site, Content $content)
    {
        abort_unless($content->site_id === $site->id, 404);
        abort_unless($site->users()->where('user_id', auth()->id())->exists(), 403);

        $mediaItems = Media::where('site_id', $site->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => ['id' => $m->id, 'src' => $m->url, 'name' => $m->name, 'type' => $m->mime_type ?? 'image'])
            ->toArray();

        $settings = $site->settings()->pluck('value', 'key');

        $brandColors = array_filter([
            $settings->get('brand_color_primary'),
            $settings->get('brand_color_secondary'),
            $settings->get('brand_color_accent'),
        ]);

        $pages = Content::where('site_id', $site->id)
            ->select('id', 'title', 'slug')
            ->orderBy('title')
            ->get()
            ->toArray();

        return view('builder.editor', [
            'site' => $site,
            'content' => $content,
            'mediaItems' => $mediaItems,
            'brandColors' => array_values($brandColors),
            'pages' => $pages,
            'contactEmail' => $settings->get('contact_email', ''),
            'backUrl' => url("/admin/{$site->id}/contents/{$content->id}/edit"),
            'gjsBlocks' => array_merge(BlockRegistry::toGrapesBlocks($content->type), $this->customBlocksToGrapesBlocks($site)),
        ]);
    }

    public function save(Request $request, Site $site, Content $content)
    {
        abort_unless($content->site_id === $site->id, 404);
        abort_unless($site->users()->where('user_id', auth()->id())->exists(), 403);

        $validated = $request->validate([
            'grapesjs_data' => ['required', 'string'],
            'grapesjs_html' => ['nullable', 'string'],
        ]);

        $content->update([
            'grapesjs_data' => $validated['grapesjs_data'],
            'grapesjs_html' => $validated['grapesjs_html'] ?? null,
        ]);

        $content->revisions()->create([
            'created_by' => auth()->id(),
            'data' => [
                'source' => 'builder',
                'grapesjs_data' => $validated['grapesjs_data'],
            ],
        ]);

        return response()->json(['ok' => true]);
    }

    public function upload(Request $request, Site $site)
    {
        abort_unless($site->users()->where('user_id', auth()->id())->exists(), 403);

        $request->validate([
            'file' => ['required', 'file', 'max:524288', 'mimes:jpg,jpeg,png,gif,webp,svg,mp4,webm,mov,avi'],
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'site_id' => $site->id,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'id' => $media->id,
            'src' => Storage::disk('public')->url($path),
            'name' => $media->name,
            'type' => $media->mime_type,
        ]);
    }

    public function contact(Request $request, Site $site)
    {
        abort_unless($site->users()->where('user_id', auth()->id())->orWhereRaw('1=1')->exists(), 200);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:5000'],
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        $to = $site->settings()->where('key', 'contact_email')->value('value');

        if (! $to) {
            return response()->json(['ok' => false, 'error' => 'No contact email configured.'], 422);
        }

        Mail::raw(
            "Jméno: {$validated['name']}\nEmail: {$validated['email']}\n\n{$validated['message']}",
            fn ($m) => $m
                ->to($to)
                ->replyTo($validated['email'], $validated['name'])
                ->subject($validated['subject'] ?? 'Nová zpráva z webu')
        );

        return response()->json(['ok' => true]);
    }

    private function customBlocksToGrapesBlocks(Site $site): array
    {
        return CustomCodeBlock::where('site_id', $site->id)
            ->orderBy('name')
            ->get()
            ->map(function (CustomCodeBlock $block): array {
                $icon = $block->icon ?? 'brackets-curly';
                $media = '<i class="fa-solid fa-'.$icon.'" style="font-size:1.5rem;display:block;margin:0 auto 4px;"></i>';

                $attrs = 'class="lx-custom-block"';
                if ($block->css_content) {
                    $attrs .= ' data-lx-css="'.base64_encode($block->css_content).'"';
                }
                if ($block->js_content) {
                    $attrs .= ' data-lx-script="'.base64_encode($block->js_content).'"';
                }

                return [
                    'id' => 'custom-code-'.$block->id,
                    'label' => $block->name,
                    'category' => __('blocks.categories.custom'),
                    'content' => '<div '.$attrs.'>'.($block->html_content ?? '').'</div>',
                    'media' => $media,
                ];
            })
            ->values()
            ->toArray();
    }
}
