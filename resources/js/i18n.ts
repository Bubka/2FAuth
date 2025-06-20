import { createI18n } from 'vue-i18n'

import type schema from '../lang/en.json'
import messages from '@intlify/unplugin-vue-i18n/messages'

export type I18nSchema = typeof schema
export type I18nLocales = 'en' | 'fr'

export default createI18n<[I18nSchema], I18nLocales>({
    legacy: false,
    locale: document.documentElement.lang,
    fallbackLocale: 'en',
    globalInjection: true,
    messages: messages as any,
})