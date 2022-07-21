<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
            <h1 class="title">{{ $t('commons.about') }}</h1>
            <p class="block">
                <span class="has-text-white"><span class="is-size-5">2FAuth</span> v{{ appVersion }}</span><br />
                A web app to manage your Two-Factor Authentication (2FA) accounts and generate their security codes
            </p>
            <img src="logo.svg" style="height: 32px" alt="logo" />
            <p class="block">
                ©Bubka <a class="is-size-7" href="https://github.com/Bubka/2FAuth/blob/master/LICENSE">AGPL-3.0 license</a>
            </p>
            <div class="buttons">
                <a class="button is-dark" href="https://github.com/Bubka/2FAuth">
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fab', 'github-alt']" />
                    </span>
                    <span>Github</span>
                </a>
                <a class="button is-dark" href="https://docs.2fauth.app/">
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'book']" />
                    </span>
                    <span>Docs</span>
                </a>
                <a class="button is-dark" href="https://demo.2fauth.app/">
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'flask']" />
                    </span>
                    <span>Demo</span>
                </a>
                <a class="button is-dark" href="https://docs.2fauth.app/resources/rapidoc.html">
                    <span class="icon is-small">
                        <font-awesome-icon :icon="['fas', 'code']" />
                    </span>
                    <span>API</span>
                </a>
            </div>
            <h2 class="title is-5 has-text-grey-light">
                {{ $t('commons.credits') }}
            </h2>
            <p class="block">
                <ul>
                    <li>Made with <a href="https://docs.2fauth.app/credits/">Laravel, Bulma CSS, Vue.js and more</a></li>
                    <li>UI Icons by <a href="https://fontawesome.com/">Font Awesome</a>&nbsp;<a class="is-size-7" href="https://fontawesome.com/license/free">(CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)</a></li>
                    <li>Logos by <a href="https://2fa.directory/">2FA Directory</a>&nbsp;<a class="is-size-7" href="https://github.com/2factorauth/twofactorauth/blob/master/LICENSE.md">(MIT License)</a></li>
                </ul>
            </p>
            <h2 class="title is-5 has-text-grey-light">
                {{ $t('commons.environment') }}
            </h2>
            <div class="box has-background-black-bis is-family-monospace is-size-7">
                <span class="is-pulled-right is-clickable" v-clipboard="() => this.$refs.listInfos.innerText" v-clipboard:success="clipboardSuccessHandler">
                    <font-awesome-icon :icon="['fas', 'copy']" />
                </span>
                <ul ref="listInfos">
                    <li v-for="(value, key) in infos" :value="value" :key="key"><b>{{key}}</b>: {{value}}</li>
                </ul>
            </div>
            <div v-if="showUserOptions">
                <h2 class="title is-5 has-text-grey-light">
                    {{ $t('settings.user_options') }}
                </h2>
                <div class="box has-background-black-bis is-family-monospace is-size-7">
                    <span class="is-pulled-right is-clickable" v-clipboard="() => this.$refs.listUserOptions.innerText" v-clipboard:success="clipboardSuccessHandler">
                        <font-awesome-icon :icon="['fas', 'copy']" />
                    </span>
                    <ul ref="listUserOptions">
                        <li v-for="(value, option) in options" :value="value" :key="option"><b>{{option}}</b>: {{value}}</li>
                    </ul>
                </div>
            </div>
            <!-- footer -->
            <vue-footer :showButtons="true">
                <!-- close button -->
                <p class="control">
                    <router-link  :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-dark is-rounded">{{ $t('commons.close') }}</router-link>
                </p>
            </vue-footer>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                infos : null,
                options : null,
                showUserOptions: false,
            }
        },

        async mounted() {
            await this.axios.get('infos').then(response => {
                this.infos = response.data

                if (response.data.options) {
                    this.options = response.data.options
                    delete this.infos.options
                    this.showUserOptions = true
                }
            })
        },

        methods: {

            clipboardSuccessHandler ({ value, event }) {

                this.$notify({ type: 'is-success', text: this.$t('commons.copied_to_clipboard') })
            },

            clipboardErrorHandler ({ value, event }) {
                console.log('error', value)
            },
        }
    }
</script>