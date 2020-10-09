<template>

</template>

<script>

    export default {
        name: 'Kicker',

        data: function () {
            return {
                events: ['click', 'mousedown', 'scroll', 'keypress', 'load'],
                logoutTimer: null
            }
        },

        mounted() {

            this.events.forEach(function (event) {
                window.addEventListener(event, this.resetTimer)
            }, this);

            this.setTimer()
        },

        destroyed() {

            this.events.forEach(function (event) {
                window.removeEventListener(event, this.resetTimer)
            }, this);

            clearTimeout(this.logoutTimer)
        },

        methods: {

            setTimer: function() {

                this.logoutTimer = setTimeout(this.logoutUser, this.$root.appSettings.kickUserAfter * 60 * 1000)
            },

            logoutUser: function() {

                clearTimeout(this.logoutTimer)

                this.appLogout()
            },

            resetTimer: function() {

                clearTimeout(this.logoutTimer)

                this.setTimer()
            }
        }
    }

</script>