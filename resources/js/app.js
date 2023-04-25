import Vue              from 'vue'
import mixins           from './mixins'
import VueStorage       from './packages/vue-storage'
import i18n             from './langs/i18n'
import router           from './routes'
import api              from './api'
import FontAwesome      from './packages/fontawesome'
import Clipboard        from './packages/clipboard'
import Notifications    from 'vue-notification'

import './components'

Vue.use(Notifications)

const app = new Vue({
    el: '#app',
    data: {
        appSettings: window.appSettings,
        appConfig: window.appConfig,
        userPreferences: window.userPreferences,
        isDemoApp: window.isDemoApp,
        isTestingApp: window.isTestingApp,
        prefersDarkScheme: window.matchMedia('(prefers-color-scheme: dark)').matches,
        spinner: {
            active: false,
            message: 'loading'
        },
    },

    computed: {
        showDarkMode: function() {
            return this.userPreferences.theme == 'dark' ||
                (this.userPreferences.theme == 'system' && this.prefersDarkScheme)
        }
    },

    mounted () {
        this.mediaQueryList = window.matchMedia('(prefers-color-scheme: dark)')
        this.$nextTick(() => {
            this.mediaQueryList.addEventListener('change', this.setDarkScheme)
        })
    },

    beforeDestroy () {
        this.mediaQueryList.removeEventListener('change', this.setDarkScheme)
    },

    methods: {
        setDarkScheme ({ matches }) {
            this.prefersDarkScheme = matches
        },

        showSpinner(message) {
            this.spinner.message = message;
            this.spinner.active = true;
        },

        hideSpinner() {
            this.spinner.active = false;
            this.spinner.message = 'loading';
        }
    },
    i18n,
    router,
});
