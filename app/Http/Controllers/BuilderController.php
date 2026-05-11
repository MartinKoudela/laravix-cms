<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Media;
use App\Models\Site;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    public function edit(Site $site, Content $content)
    {
        abort_unless($content->site_id === $site->id, 404);
        abort_unless($site->users()->where('user_id', auth()->id())->exists(), 403);

        $mediaItems = Media::where('site_id', $site->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => ['id' => $m->id, 'src' => $m->url, 'name' => $m->name])
            ->toArray();

        return view('builder.editor', [
            'site' => $site,
            'content' => $content,
            'mediaItems' => $mediaItems,
            'backUrl' => url("/admin/{$site->id}/contents/{$content->id}/edit"),
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

        return response()->json(['ok' => true]);
    }
}
