<script setup>
    import systemService from '@/services/systemService'
    import { UseColorMode } from '@vueuse/components'
    import CopyButton from '@/components/CopyButton.vue'

    const $2fauth = inject('2fauth')
    const router = useRouter()

    const returnTo = router.options.history.state.back
    const infos = ref()
    const listInfos = ref(null)
    const userPreferences = ref(false)
    const listUserPreferences = ref(null)
    const adminSettings = ref(false)
    const listAdminSettings = ref(null)

    onMounted(() => {
        systemService.getSystemInfos({returnError: true}).then(response => {
            infos.value = response.data.common

            if (response.data.admin_settings) {
                adminSettings.value = response.data.admin_settings
            }

            if (response.data.user_preferences) {
                userPreferences.value = response.data.user_preferences
            }
        })
        .catch(() => {
            infos.value = null
        })
    })
</script>

<template>
    <ResponsiveWidthWrapper>
        <h1 class="title has-text-grey-dark">{{ $t('commons.about') }}</h1>
        <p class="block">
            <UseColorMode v-slot="{ mode }">
                <span :class="mode == 'dark' ? 'has-text-white':'has-text-black'"><span class="is-size-5">2FAuth</span> v{{ $2fauth.version }}</span>
            </UseColorMode>
            <br />
            {{ $t('commons.2fauth_teaser')}}
        </p>
        <img class="about-logo" src="logo.svg" alt="2FAuth logo" />
        <p class="block">
            ©Bubka <a class="is-size-7" href="https://github.com/Bubka/2FAuth/blob/master/LICENSE">AGPL-3.0 license</a>
        </p>
        <h2 class="title is-5 has-text-grey-light">
            {{ $t('commons.resources') }}
        </h2>
        <div class="buttons">
            <UseColorMode v-slot="{ mode }">
                <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://github.com/Bubka/2FAuth" target="_blank">
                    <span class="icon is-small">
                        <FontAwesomeIcon :icon="['fab', 'github-alt']" />
                    </span>
                    <span>Github</span>
                </a>
                <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://docs.2fauth.app/" target="_blank">
                    <span class="icon is-small">
                        <FontAwesomeIcon :icon="['fas', 'book']" />
                    </span>
                    <span>Docs</span>
                </a>
                <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://demo.2fauth.app/" target="_blank">
                    <span class="icon is-small">
                        <FontAwesomeIcon :icon="['fas', 'flask']" />
                    </span>
                    <span>Demo</span>
                </a>
                <a class="button" :class="{'is-dark' : mode == 'dark'}" href="https://docs.2fauth.app/resources/rapidoc.html" target="_blank">
                    <span class="icon is-small">
                        <FontAwesomeIcon :icon="['fas', 'code']" />
                    </span>
                    <span>API</span>
                </a>
            </UseColorMode>
        </div>
        <h2 class="title is-5 has-text-grey-light">
            {{ $t('commons.credits') }}
        </h2>
        <p class="block">
            <ul>
                <li>{{ $t('commons.made_with') }}&nbsp;<a href="https://docs.2fauth.app/credits/">Laravel, Bulma CSS, Vue.js and more</a></li>
                <li>{{ $t('commons.ui_icons_by') }}&nbsp;<a href="https://fontawesome.com/">Font Awesome</a>&nbsp;<a class="is-size-7" href="https://fontawesome.com/license/free">(CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)</a></li>
                <li>{{ $t('commons.logos_by') }}&nbsp;<a href="https://2fa.directory/">2FA Directory</a>&nbsp;<a class="is-size-7" href="https://github.com/2factorauth/twofactorauth/blob/master/LICENSE.md">(MIT License)</a></li>
            </ul>
        </p>
        <h2 class="title is-5 has-text-grey-light">
            {{ $t('commons.environment') }}
        </h2>
        <div v-if="infos" class="about-debug box is-family-monospace is-size-7">
            <CopyButton id="btnCopyEnvVars" :token="listInfos?.innerText" />
            <ul ref="listInfos" id="listInfos">
                <li v-for="(value, key) in infos" :value="value" :key="key"><b>{{key}}</b>: {{value}}</li>
            </ul>
        </div>
        <div v-else-if="infos === null" class="about-debug box is-family-monospace is-size-7 has-text-warning-dark">
            {{ $t('errors.error_during_data_fetching') }}
        </div>
        <h2 v-if="adminSettings" class="title is-5 has-text-grey-light">
            {{ $t('settings.admin_settings') }}
        </h2>
        <div v-if="adminSettings" class="about-debug box is-family-monospace is-size-7">
            <CopyButton id="btnCopyAdminSettings" :token="listAdminSettings?.innerText" />
            <ul ref="listAdminSettings" id="listAdminSettings">
                <li v-for="(value, setting) in adminSettings" :value="value" :key="setting"><b>{{setting}}</b>: {{value}}</li>
            </ul>
        </div>
        <h2 v-if="userPreferences" class="title is-5 has-text-grey-light">
            {{ $t('settings.user_preferences') }}
        </h2>
        <div v-if="userPreferences" class="about-debug box is-family-monospace is-size-7">
            <CopyButton id="btnCopyUserPreferences" :token="listUserPreferences?.innerText" />
            <ul ref="listUserPreferences" id="listUserPreferences">
                <li v-for="(value, preference) in userPreferences" :value="value" :key="preference"><b>{{preference}}</b>: {{value}}</li>
            </ul>
        </div>
        <!-- footer -->
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ path: returnTo }" action="back" />
        </VueFooter>
    </ResponsiveWidthWrapper>
</template>