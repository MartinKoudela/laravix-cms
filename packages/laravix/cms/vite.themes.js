import { build } from 'vite';
import { existsSync, readdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const themesDir = path.join(path.dirname(fileURLToPath(import.meta.url)), 'resources/themes');
const watch = process.argv.includes('--watch');

const themes = readdirSync(themesDir, { withFileTypes: true })
    .filter((entry) => entry.isDirectory())
    .map((entry) => {
        const root = path.join(themesDir, entry.name);
        const input = Object.fromEntries(
            [['app-css', 'assets/css/app.css'], ['app', 'assets/js/app.js']]
                .map(([name, file]) => [name, path.join(root, file)])
                .filter(([, file]) => existsSync(file)),
        );

        return { key: entry.name, root, input };
    })
    .filter((theme) => Object.keys(theme.input).length > 0);

if (themes.length === 0) {
    console.log('No bundled theme has assets to build.');
}

for (const theme of themes) {
    await build({
        configFile: false,
        root: theme.root,
        logLevel: 'warn',
        build: {
            outDir: path.join(theme.root, 'dist'),
            emptyOutDir: true,
            watch: watch ? {} : null,
            rollupOptions: {
                input: theme.input,
                output: {
                    entryFileNames: '[name].js',
                    chunkFileNames: '[name].js',
                    assetFileNames: (asset) => (asset.names?.[0] ?? asset.name ?? '').endsWith('.css') ? 'app.css' : '[name][extname]',
                },
            },
        },
    });

    console.log(`Built theme "${theme.key}"`);
}
