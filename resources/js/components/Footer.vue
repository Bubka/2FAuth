<template>
    <footer class="has-background-black-ter">
        <div class="columns is-gapless" v-if="showButtons">
            <div class="column has-text-centered">
                <div class="field is-grouped">
                    <slot></slot>
                </div>
            </div>
        </div>
        <div v-if="$route.name === 'settings'" class="content has-text-centered is-size-6">
            <a class="has-text-grey" href="https://github.com/Bubka/2FAuth"><b>2FAuth</b> <font-awesome-icon :icon="['fab', 'github-alt']" /></a> - v{{ appVersion }}
        </div>
        <div v-else class="content has-text-centered">
            <router-link :to="{ name: 'settings' }" class="has-text-grey">{{ $t('settings.settings') }}</router-link> - <a class="has-text-grey" @click="logout">{{ $t('auth.sign_out') }}</a>
        </div>
    </footer>
</template>

<script>
    export default {
        name: 'VueFooter',

        data(){
            return {
            }
        },

        props: {
            showButtons: true,
        },

        methods: {
            logout() {
                if(confirm(this.$t('auth.confirm.logout'))) {

                    this.appLogout()
                }
            }
        }
    };
</script>
