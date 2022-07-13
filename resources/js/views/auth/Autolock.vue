<template>
    <form-wrapper :title="$t('auth.autolock_triggered')" :punchline="$t('auth.autolock_triggered_punchline')">
        <p>{{ $t('auth.change_autolock_in_settings') }}</p>
        <div class="nav-links">
            <p><router-link :to="{ name: 'login', params: {forceRefresh : true} }" class="button is-link">{{ $t('auth.sign_in') }}</router-link></p>
        </div>
    </form-wrapper>
</template>

<script>
    export default {
        data(){
            return {
            }
        },

        mounted() {
            this.axios.get('/user/logout', {returnError: true}).catch(error => {
                // there is nothing to do, we simply catch the error to avoid redondant navigation
            });
            
            this.$storage.clear()
        },
    }
</script>