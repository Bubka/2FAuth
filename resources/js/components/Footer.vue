<template>
    <footer class="has-background-black-ter">
        <div class="columns is-gapless" v-if="showButtons">
            <div class="column has-text-centered">
                <div class="field is-grouped">
                    <slot></slot>
                </div>
            </div>
        </div>
        <div class="content has-text-centered">
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

            async logout(evt) {
                if(confirm(this.$t('auth.confirm.logout'))) {

                    await this.axios.get('api/logout')

                    localStorage.removeItem('jwt')
                    localStorage.removeItem('user')

                    delete this.axios.defaults.headers.common['Authorization']

                    this.$router.push({ name: 'login' })
                }
            },

        }
    };
</script>
