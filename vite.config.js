import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['app/Portal/Assets/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/app/Portal/Assets',
        },
    },
});
