<template>
    <div>
        <modal v-model="ShowModal">
            <div v-if="$route.name == '404'">
                <p class="error-404"></p>
                <p>{{ $t('errors.resource_not_found') }}<router-link :to="{ name: 'accounts' }" class="is-text has-text-white">{{ $t('errors.refresh') }}</router-link></p>
            </div>
            <div v-else-if="$route.name == 'flooded'">
                <p class="error-generic"></p>
                <p>{{ $t('errors.already_one_user_registered') }}<br>
                    {{ $t('errors.cannot_register_more_user') }}<br><br>
                    {{ $t('errors.please') }}<router-link :to="{ name: 'accounts' }" class="is-text has-text-white">{{ $t('auth.sign_in') }}</router-link></p>
            </div>
            <div v-else>
                <p class="error-generic"></p>
                <p>{{ $t('errors.error_occured') }}<router-link :to="{ name: 'accounts' }" class="is-text has-text-white">{{ $t('errors.refresh') }}</router-link></p>
            </div>
            <div v-if="debugMode == 'development'">
                <p v-if="debug" class="debug">
                    <code>
                        {{ debug }}
                    </code>
                </p>
            </div>
        </modal>
    </div>
</template>


<script>
    import Modal from '../components/Modal'

    export default {
        data(){
            return {
                ShowModal : true,
                debug : this.err ? this.err.data : null,
            }
        },

        computed: {
            debugMode: function() {
                return process.env.NODE_ENV
            }
        },

        props: ['err'],

        components: {
            Modal
        },

        mounted(){
            // stop OTP generation on modal close
            this.$on('modalClose', function() {
                this.$router.push({name: 'accounts' });
            });

        }
    }

</script>

