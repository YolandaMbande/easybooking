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
        outDir: 'public/build',  // Ensures output goes to public/build
        manifest: true,          // Enable manifest.json generation
        rollupOptions: {
          output: {
            entryFileNames: 'assets/[name]-[hash].js', // Organizes files in assets/
            chunkFileNames: 'assets/[name]-[hash].js',
            assetFileNames: 'assets/[name]-[hash].[ext]'
          }
        }
    },
});
