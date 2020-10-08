import Vue from 'vue'

Vue.mixin({

    data: function () {
        return {
            appVersion: window.appVersion
        }
    },

    methods: {

        async appLogout(evt) {

            await this.axios.get('api/logout')

            localStorage.removeItem('jwt')
            localStorage.removeItem('user')

            delete this.axios.defaults.headers.common['Authorization']

            this.$router.push({ name: 'login' })
        },
    }

})