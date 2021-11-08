import Vue from 'vue'

Vue.mixin({

    data: function () {
        return {
            appVersion: window.appVersion
        }
    },

    methods: {

        async appLogout(evt) {

            await this.axios.get('/user/logout')

            this.$storage.clear()
            delete this.axios.defaults.headers.common['Authorization']

            this.$router.push({ name: 'login' })
        },
        
        exitSettings: function(event) {
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
            a.dispatchEvent(new MouseEvent("click", {'view': window, 'bubbles': true, 'cancelable': true}))
        }
    }

})