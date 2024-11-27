import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import i18n from 'laravel-vue-i18n/vite'
import AutoImport from 'unplugin-auto-import/vite'
import version from './vite.version'

const ASSET_URL = process.env.ASSET_URL || ''

export default defineConfig({
    base: `${ASSET_URL}`,
    plugins: [
        laravel([
            'resources/js/app.js',
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
                        'useNavigatorLanguage'
                    ],
                    'laravel-vue-i18n': [
                        'i18nVue',
                        'trans',
                        'wTrans',
                        'getActiveLanguage',
                        'loadLanguageAsync',
                        'getActiveLanguage'
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
            '@': '/resources/js',
        },
    },
    build: {
        sourcemap: true,
        rollupOptions: {
            output: {
                banner: '/*! 2FAuth version ' + version + ' - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */',
            },
        },
    },
    // server: {
    //     watch: {
    //         followSymlinks: false,
    //     }
    // }
});
