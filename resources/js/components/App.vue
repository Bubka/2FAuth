<template>
    <div>
        <kicker v-if="kickInactiveUser"></kicker>
        <div v-if="this.$root.isDemoApp" class="demo has-background-warning has-text-centered is-size-7-mobile">
            {{ $t('commons.demo_do_not_post_sensitive_data') }}
        </div>
        <div v-if="this.$root.isTestingApp" class="demo has-background-warning has-text-centered is-size-7-mobile">
            {{ $t('commons.testing_do_not_post_sensitive_data') }}
        </div>
        <notifications id="vueNotification" role="alert" width="100%" position="top" :duration="4000" :speed="0" :max="1" classes="notification is-radiusless" />
        <main class="main-section">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'App',
        
        data(){
            return {
            }
        },

        computed: {

            kickInactiveUser: function () {
                return parseInt(this.$root.appSettings.kickUserAfter) > 0 && this.$route.meta.requiresAuth
            }

        }
    }
</script>