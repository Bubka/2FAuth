import Vue  from 'vue'
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';


Vue.use(VueInternationalization);

// const lang = document.documentElement.lang.substr(0, 2);
const lang = 'en';

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

export default i18n