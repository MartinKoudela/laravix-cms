import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const dir = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
    root: dir,
    plugins: [tailwindcss()],
    build: {
        outDir: path.join(dir, 'dist'),
        emptyOutDir: true,
        rollupOptions: {
            input: {
                'app': path.join(dir, 'resources/css/app.css'),
                'frontend': path.join(dir, 'resources/js/app.js'),
                'builder': path.join(dir, 'resources/js/builder.js'),
                'filament-theme': path.join(dir, 'resources/css/filament/admin/theme.css'),
                'filament-app': path.join(dir, 'resources/js/filament/admin/app.js'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name][extname]',
            },
        },
    },
});
