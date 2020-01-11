<template>
    <div>
        <modal v-model="ShowModal">
            <div v-if="$route.name == '404'">
                <p class="error-404"></p>
                <p>Resource not found, please <router-link :to="{ name: 'accounts' }" class="is-text has-text-white">refresh</router-link></p>
            </div>
            <div v-else>
                <p class="error-generic"></p>
                <p>An error occured, please <router-link :to="{ name: 'accounts' }" class="is-text has-text-white">refresh</router-link></p>
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

        },
        
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }

</script>

