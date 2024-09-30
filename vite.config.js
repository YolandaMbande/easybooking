import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',  // SCSS file
                'resources/js/app.js',      // Main JS file
            ],
            refresh: true,
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
            vue: 'vue/dist/vue.esm-bundler.js',  // Alias for Vue
        },
    },
    build: {
        outDir: 'public/build',  // Specify output directory for production build
        manifest: true,          // Ensure manifest.json is generated
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',  // Input for the main app
            },
        },
    },
});
