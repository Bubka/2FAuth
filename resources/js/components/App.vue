<template>
    <div>
        <kicker v-if="kickInactiveUser"></kicker>
        <div v-if="this.$root.isDemoApp" class="demo has-background-warning has-text-centered is-size-7-mobile">
            {{ $t('commons.demo_do_not_post_sensitive_data') }}
        </div>
        <div v-if="this.$root.isTestingApp" class="demo has-background-warning has-text-centered is-size-7-mobile">
            {{ $t('commons.testing_do_not_post_sensitive_data') }}
        </div>
        <!-- Loading spinner -->
        <spinner :active="$root.spinner.active" :message="$root.spinner.message"/>
        <notifications id="vueNotification" role="alert" width="100%" position="top" :duration="4000" :speed="0" :max="1" classes="notification is-radiusless" />
        <main class="main-section">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    import Spinner from "./Spinner.vue";

    export default {
        name: 'App',
        components: {
            Spinner
        },

        data(){
            return {
            }
        },

        computed: {

            kickInactiveUser: function () {
                return parseInt(this.$root.userPreferences.kickUserAfter) > 0 && this.$route.meta.requiresAuth
            }

        }
    }
</script>
