import Vue  from 'vue'
import VueInternationalization from 'vue-i18n';
import Locale from './locales';

Vue.use(VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

export default i18n