import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import i18n from 'laravel-vue-i18n/vite';
import AutoImport from 'unplugin-auto-import/vite';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js_vue3/app.js',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
        i18n('resources/lang'),
        AutoImport({
            // https://github.com/unplugin/unplugin-auto-import?tab=readme-ov-file#configuration
            imports: [
                'vue',
                'vue-router',
                'pinia',
                {
                    '@vueuse/core': [
                        'useStorage',
                        'useClipboard',
                    ],
                    'laravel-vue-i18n': [
                        'i18nVue',
                        'trans'
                    ],
                    '@kyvg/vue3-notification': [
                        'useNotification'
                    ],
                },
            ],
            // resolvers: [
            //     ElementPlusResolver(),
            // ],
            dirs: [
                '@/composables/**',
            ],
            vueTemplate: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js_vue3',
        },
    },
});