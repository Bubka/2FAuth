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
        }
    }

})