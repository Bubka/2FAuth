import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vueI18n from '@intlify/unplugin-vue-i18n/vite'
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
        vueI18n({
            include: 'resources/lang/*.json'
        }),
        AutoImport({
            // https://github.com/unplugin/unplugin-auto-import?tab=readme-ov-file#configuration
            include: [
                /\.[tj]sx?$/, // .ts, .tsx, .js, .jsx
                /\.vue$/,
                /\.vue\?vue/, // .vue
            ],
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
                    '@kyvg/vue3-notification': [
                        'useNotification'
                    ],
                },
            ],
            // resolvers: [
            //     ElementPlusResolver(),
            // ],
            dirs: [
                './resources/js/components/**',
                './resources/js/composables/**',
                './resources/js/layouts/**',
                './resources/js/router/**',
                './resources/js/services/**',
                './resources/js/stores/**',
            ],
            vueTemplate: true,
            vueDirectives: true,
            dts: './auto-imports.d.ts',
            viteOptimizeDeps: true,
            eslintrc: {
                enabled: true,
                filepath: './.eslintrc-auto-import.mjs',
                globalsPropValue: true, // 'readonly',
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
        dedupe: [
            'pinia',
            '@kyvg/vue3-notification',
        ],
    },
    build: {
        // sourcemap: true,
        rollupOptions: {
            output: {
                banner: '/*! 2FAuth version ' + version + ' - Copyright (c) 2025 Bubka - https://github.com/Bubka/2FAuth */',
            },
        },
    },
    server: {
        cors: true, // Configure CORS for the dev server. Pass an options object to fine tune the behavior or true to allow any origin
        // watch: {
        //     followSymlinks: false,
        // }
    }
});
