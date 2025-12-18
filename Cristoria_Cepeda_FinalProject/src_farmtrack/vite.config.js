import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/dashboard.js',
                'resources/js/crops-create.js',
                'resources/js/crops-edit.js',
                'resources/js/farmers-create.js',
                'resources/js/progress-logs-create.js',
                'resources/js/progress-logs-edit.js'
            ],
            refresh: true,
        }),
    ],
});
