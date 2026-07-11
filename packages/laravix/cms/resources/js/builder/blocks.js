export function registerBlocks(editor, blocks) {
    const bm = editor.BlockManager;

    for (const block of blocks) {
        bm.add(block.id, {
            label: block.label,
            category: block.category,
            media: block.media || '',
            content: block.content,
        });
    }
}