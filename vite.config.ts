import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['app/Portal/Assets/ts/app.ts'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/app/Portal/Assets',
        },
    },
});
