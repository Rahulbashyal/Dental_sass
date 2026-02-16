import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/animations.css',
                'resources/css/nepali-datepicker.css',
                'resources/js/app.js',
                'resources/js/nepali-datepicker.js'
            ],
            refresh: true,
        }),
    ],
});