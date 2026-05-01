import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css', 'resources/js/filament/admin/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: Number(process.env.VITE_PORT) || 5173,
        hmr: {
            host: 'localhost',
            port: Number(process.env.VITE_PORT) || 5173,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
