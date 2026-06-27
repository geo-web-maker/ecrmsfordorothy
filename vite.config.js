import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/landing.css', 'resources/css/welcome-page.css', 'resources/css/splash-page.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
    },
    build: {
        minify: true,
        cssMinify: true,
        sourcemap: false,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                },
            },
        },
    },
});
