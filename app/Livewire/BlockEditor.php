<?php

namespace App\Livewire;

use Livewire\Component;

class BlockEditor extends Component
{
    public int $contentId;
    public string $previewToken;
    public array $blocks = [];
    public ?int $editingIndex = null;


    public function render()
    {
        return view('livewire.block-editor');
    }

    public function mount(int $contentId, string $previewToken)
    {
        $this->contentId = $contentId;
        $this->previewToken = $previewToken;
        $this->blocks = \App\Models\Content::find($contentId)?->blocks ?? [];

    }

    public function editBlock(int $index)
    {
        $this->editingIndex = $index;
    }

    public function addBlock(string $type)
    {
        $this->blocks[] = ['type' => $type, 'data' => [], 'id' => uniqid('', true)];
        $this->editingIndex = array_key_last($this->blocks);
        $this->refreshPreview();
    }

    public function removeBlock(int $index)
    {
        array_splice($this->blocks, $index, 1);
        $this->blocks = array_values($this->blocks);
        $this->refreshPreview();
    }

    public function reorderBlocks(array $order)
    {

        foreach ($order as $index) {
            $reordered[] = $this->blocks[$index];
        }
        $this->blocks = $reordered;
        $this->refreshPreview();

    }

    public function updateField(string $key, mixed $value)
    {
        $this->blocks[$this->editingIndex]['data'][$key] = $value;
        $this->refreshPreview();

    }

    public function backToList()
    {
        $this->editingIndex = null;
    }

    public function save()
    {
        \App\Models\Content::find($this->contentId)->update(['blocks' => $this->blocks]);
        $this->dispatch('blocks-saved');

    }

    private function refreshPreview(): void
    {
        cache()->put("preview_blocks_{$this->previewToken}", [
            'blocks' => $this->blocks,
        ], now()->addMinutes(30));

        $this->dispatch('block-preview-updated');
    }

}
