import Vue from 'vue'
import i18n from './langs/i18n'

Vue.mixin({

    data: function () {
        return {
            appVersion: window.appVersion
        }
    },

    methods: {

        async appLogout(evt) {
            if (this.$root.appConfig.proxyAuth) {
                if (this.$root.appConfig.proxyLogoutUrl) {
                    location.assign(this.$root.appConfig.proxyLogoutUrl)
                }
                else return false
            }
            else {
                await this.axios.get('/user/logout')
                this.clearStorage()
                this.$router.push({ name: 'login', params: { forceRefresh: true } })
            }
        },

        clearStorage() {
            this.$storage.set('accounts')
            this.$storage.set('groups')
            this.$storage.set('lastRoute')
        },

        exitSettings: function (event) {
            if (event) {
                this.$notify({ clean: true })
                this.$router.push({ name: 'accounts' })
            }
        },

        isUrl: function (url) {
            var strRegex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
            var re = new RegExp(strRegex)

            return re.test(url)
        },

        openInBrowser(uri) {
            const a = document.createElement('a')
            a.setAttribute('href', uri)
            a.dispatchEvent(new MouseEvent("click", { 'view': window, 'bubbles': true, 'cancelable': true }))
        },

        /**
         * 
         */
        inputId(fieldType, fieldName) {
            let prefix
            fieldName = fieldName.toString()

            switch (fieldType) {
                case 'button':
                    prefix = 'txt'
                    break
                case 'button':
                    prefix = 'btn'
                    break
                case 'email':
                    prefix = 'eml'
                    break
                case 'password':
                    prefix = 'pwd'
                    break
                case 'radio':
                    prefix = 'rdo'
                    break
                case 'label':
                    prefix = 'lbl'
                    break
                default:
                    prefix = 'txt'
                    break
            }

            return prefix + fieldName[0].toUpperCase() + fieldName.toLowerCase().slice(1);
            // button
            // checkbox
            // color
            // date 
            // datetime-local
            // file
            // hidden
            // image
            // month
            // number
            // radio
            // range
            // reset
            // search
            // submit
            // tel
            // text
            // time
            // url
            // week
        },

        setTheme(theme) {
            document.documentElement.dataset.theme = theme;
        },

        applyPreferences(preferences) {
            for (const preference in preferences) {
                try {
                    this.$root.userPreferences[preference] = preferences[preference]
                 }
                 catch (e) {
                    console.log(e)
                 }
            }

            if (this.$root.userPreferences.lang != 'browser') {
                i18n.locale = this.$root.userPreferences.lang
                document.documentElement.lang = this.$root.userPreferences.lang
            }
        },
    }

})