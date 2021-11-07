<template>
    <div>
        <setting-tabs :activeTab="'settings.oauth'"></setting-tabs>
        <div class="options-tabs">
            <div class="columns is-centered">
                <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
                    <h4 class="title is-4 has-text-grey-light">{{ $t('settings.personal_access_tokens') }}</h4>
                    <div class="is-size-7-mobile">
                        {{ $t('settings.token_legend')}}
                    </div>
                    <div class="mt-3 mb-6">
                        <router-link class="is-link mt-5" :to="{ name: 'settings.oauth.generatePAT' }">
                            <font-awesome-icon :icon="['fas', 'plus-circle']" /> {{ $t('settings.generate_new_token')}}
                        </router-link>
                    </div>
                    <div v-if="tokens.length > 0">
                        <div v-for="token in tokens" :key="token.id" class="group-item has-text-light is-size-5 is-size-6-mobile">
                            <font-awesome-icon v-if="token.value" class="has-text-success" :icon="['fas', 'check']" /> {{ token.name }}
                            <div class="tags is-pulled-right">
                                <a v-if="token.value" class="tag" v-clipboard="() => token.value" v-clipboard:success="clipboardSuccessHandler">{{ $t('commons.copy') }}</a>
                                <a class="tag is-dark " @click="revokeToken(token.id)">{{ $t('settings.revoke') }}</a>
                            </div>
                            <span v-if="token.value" class="is-size-7-mobile is-size-6 my-3">
                                {{ $t('settings.make_sure_copy_token') }}
                            </span>
                            <span v-if="token.value" class="pat is-family-monospace is-size-6 is-size-7-mobile has-text-success">
                                {{ token.value }}
                            </span>
                        </div>
                    </div>
                    <div v-if="isFetching && tokens.length === 0" class="has-text-centered">
                        <span class="is-size-4">
                            <font-awesome-icon :icon="['fas', 'spinner']" spin />
                        </span>
                    </div>
                    <!-- footer -->
                    <vue-footer :showButtons="true">
                        <!-- close button -->
                        <p class="control">
                            <router-link :to="{ name: 'accounts', params: { toRefresh: false } }" class="button is-dark is-rounded">{{ $t('commons.close') }}</router-link>
                        </p>
                    </vue-footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                tokens : [],
                isFetching: false,
                form: new Form({
                    token : '',
                })
            }
        },

        mounted() {
            this.fetchTokens()
        },

        methods : {

            /**
             * Get all groups from backend
             */
            async fetchTokens() {

                this.isFetching = true

                await this.axios.get('/api/v1/oauth/personal-access-tokens').then(response => {
                    const tokens = []

                    response.data.forEach((data) => {
                        if (data.id === this.$route.params.token_id) {
                            data.value = this.$route.params.accessToken
                            tokens.unshift(data)
                        }
                        else {
                            tokens.push(data)
                        }
                    })

                    this.tokens = tokens
                })

                this.isFetching = false
            },

            clipboardSuccessHandler ({ value, event }) {

                this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
            },

            clipboardErrorHandler ({ value, event }) {
                console.log('error', value)
            },

            /**
             * revoke a token (after confirmation)
             */
            async revokeToken(tokenId) {
                if(confirm(this.$t('settings.confirm.revoke'))) {

                    await this.axios.delete('/api/v1/oauth/personal-access-tokens/' + tokenId).then(response => {
                        // Remove the revoked token from the collection
                        this.tokens = this.tokens.filter(a => a.id !== tokenId)
                        this.$notify({ type: 'is-success', text: this.$t('settings.token_revoked') })
                    });
                }
            }
        },
    }
</script>