import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/rating.js',
                'resources/css/admin/admin.css',
                'resources/js/admin/admin.js',
                'resources/css/pages/dashboard.css',
                'resources/js/pages/dashboard.js'
            ],
            refresh: true,
        }),
    ],
});
