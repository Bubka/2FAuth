import Vue from 'vue'

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
                this.$storage.clear()
                this.$router.push({ name: 'login', params: { forceRefresh: true } })
            }
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
        }
    }

})