<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Livewire;

use App\Models\Content;
use App\Models\Media;
use App\Support\BlockRegistry;
use Livewire\Component;

class BlockEditor extends Component
{
    public int $contentId;

    public string $previewToken;

    public array $blocks = [];

    public ?int $editingIndex = null;

    public int $siteId = 0;

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'blocks.')) {
            $this->refreshPreview();
        }
    }

    public function render()
    {
        if (! $this->siteId) {
            $this->siteId = Content::find($this->contentId)?->site_id ?? 0;
        }

        $blockFields = [];
        $mediaItems = [];

        if ($this->editingIndex !== null && isset($this->blocks[$this->editingIndex])) {
            $type = $this->blocks[$this->editingIndex]['type'];
            $definition = BlockRegistry::all()[$type] ?? null;

            if ($definition) {
                $blockFields = $definition->toEditorFields();
                $hasImageField = collect($blockFields)->contains('type', 'image')
                    || collect($blockFields)
                        ->where('type', 'repeater')
                        ->flatMap(fn ($f) => $f['fields'] ?? [])
                        ->contains('type', 'image');

                if ($hasImageField && $this->siteId) {
                    $mediaItems = Media::where('site_id', $this->siteId)
                        ->orderByDesc('created_at')
                        ->limit(50)
                        ->get()
                        ->map(fn ($m) => ['id' => $m->id, 'name' => $m->name, 'url' => $m->url])
                        ->toArray();
                }
            }
        }

        $blockTypes = collect(BlockRegistry::all())
            ->map(fn ($def) => ['key' => $def->key, 'label' => $def->label, 'icon' => $def->icon])
            ->values()
            ->toArray();

        return view('livewire.block-editor', compact('blockFields', 'mediaItems', 'blockTypes'));
    }

    public function mount(int $contentId, string $previewToken): void
    {
        $this->contentId = $contentId;
        $this->previewToken = $previewToken;
        $content = Content::find($contentId);
        $this->blocks = $content?->blocks ?? [];
        $this->siteId = $content?->site_id ?? 0;
    }

    public function editBlock(int $index): void
    {
        $this->editingIndex = $index;
    }

    public function addBlock(string $type): void
    {
        $this->blocks[] = ['type' => $type, 'data' => [], 'id' => uniqid('', true)];
        $this->editingIndex = array_key_last($this->blocks);
        $this->refreshPreview();
    }

    public function removeBlock(int $index): void
    {
        array_splice($this->blocks, $index, 1);
        $this->blocks = array_values($this->blocks);
        $this->refreshPreview();
    }

    public function reorderBlocks(array $order): void
    {
        $reordered = [];
        foreach ($order as $index) {
            if (isset($this->blocks[$index])) {
                $reordered[] = $this->blocks[$index];
            }
        }
        $this->blocks = $reordered;
        $this->refreshPreview();
    }

    public function addRepeaterItem(string $fieldKey): void
    {
        $this->blocks[$this->editingIndex]['data'][$fieldKey][] = [];
        $this->refreshPreview();
    }

    public function removeRepeaterItem(string $fieldKey, int $itemIndex): void
    {
        array_splice($this->blocks[$this->editingIndex]['data'][$fieldKey], $itemIndex, 1);
        $this->blocks[$this->editingIndex]['data'][$fieldKey] = array_values(
            $this->blocks[$this->editingIndex]['data'][$fieldKey]
        );
        $this->refreshPreview();
    }

    public function updateField(string $key, mixed $value): void
    {
        $this->blocks[$this->editingIndex]['data'][$key] = $value;
        $this->refreshPreview();
    }

    public function updateRepeaterField(string $fieldKey, int $itemIndex, string $subFieldKey, mixed $value): void
    {
        $this->blocks[$this->editingIndex]['data'][$fieldKey][$itemIndex][$subFieldKey] = $value;
        $this->refreshPreview();
    }

    public function backToList(): void
    {
        $this->editingIndex = null;
    }

    public function save(): void
    {
        Content::find($this->contentId)->update(['blocks' => $this->blocks]);
        $this->dispatch('blocks-saved');
    }

    private function refreshPreview(): void
    {
        cache()->put('preview_blocks_'.$this->previewToken, [
            'content_id' => $this->contentId,
            'blocks' => $this->blocks,
        ], now()->addMinutes(30));

        $this->dispatch('block-preview-updated');
    }
}
