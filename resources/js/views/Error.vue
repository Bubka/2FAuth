<template>
    <div class="error-message">
        <modal v-model="ShowModal">
            <div class="error-message" v-if="$route.name == '404'">
                <p class="error-404"></p>
                <p>{{ $t('errors.resource_not_found') }}</p>
                <p class=""><router-link :to="{ name: 'accounts' }" class="is-text">{{ $t('errors.refresh') }}</router-link></p>
            </div>
            <div v-else-if="$route.name == 'flooded'">
                <p class="error-generic"></p>
                <p>
                    {{ $t('errors.already_one_user_registered') }}<br>
                    {{ $t('errors.cannot_register_more_user') }}
                </p>
                <p><router-link :to="{ name: 'accounts' }" class="is-text">{{ $t('auth.sign_in') }}</router-link></p>
            </div>
            <div v-else>
                <p class="error-generic"></p>
                <p>{{ $t('errors.error_occured') }} </p>
                <p v-if="error" class="has-text-grey-lighter">{{ error.message }}</p>
                <p v-if="error.originalMessage" class="has-text-grey-lighter">{{ error.originalMessage }}</p>
                <p><router-link :to="{ name: 'accounts' }" class="is-text">{{ $t('errors.refresh') }}</router-link></p>
                <p v-if="debugMode == 'development' && error.debug">
                    <br>
                    {{ error.debug }}
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
            }
        },

        computed: {

            debugMode: function() {
                return process.env.NODE_ENV
            },

            error: function() {
                if( this.err === undefined ) {
                    return null
                }
                else
                {
                    if(this.err.data) {
                        console.log(this.err.data)
                        return this.err.data
                    }
                    else
                    {
                        return { 'message' : this.err }
                    }

                }
            }

        },

        props: ['err'], // error.response

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

