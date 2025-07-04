import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vue: ['vue', '@inertiajs/vue3'],
                    flowbite: ['flowbite'],
                }
            }
        },
        minify: 'esbuild',
        sourcemap: false,
          
    },
    define: {
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: true
    }
});
