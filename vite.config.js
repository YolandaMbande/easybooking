import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',  // Your SASS/SCSS entry point
                'resources/js/app.js',     // Your main JS entry point
            ],
            refresh: true,  // Enables automatic page refresh during development
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',  // Vue's alias for templates
        },
    },
    build: {
        outDir: 'public/build',  // Ensure output directory is correct
        manifest: true,          // Generate the manifest.json
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',  // Main JS file for the app
            },
        },
    },
});
