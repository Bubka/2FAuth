<script setup>
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import groupService from '@/services/groupService'
    import userPreferenceService from '@/services/userPreferenceService'
    import { useUserStore } from '@/stores/user'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import VersionChecker from '@/components/VersionChecker.vue'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotifyStore()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const layouts = [
        { text: 'settings.forms.grid', value: 'grid', icon: 'th' },
        { text: 'settings.forms.list', value: 'list', icon: 'list' },
    ]
    const themes = [
        { text: 'settings.forms.light', value: 'light', icon: 'sun' },
        { text: 'settings.forms.dark', value: 'dark', icon: 'moon' },
        { text: 'settings.forms.automatic', value: 'system', icon: 'desktop' },
    ]
    const passwordFormats = [
        { text: '12 34 56', value: 2, legend: 'settings.forms.pair', title: 'settings.forms.pair_legend' },
        { text: '123 456', value: 3, legend: 'settings.forms.trio', title: 'settings.forms.trio_legend' },
        { text: '1234 5678', value: 0.5, legend: 'settings.forms.half', title: 'settings.forms.half_legend' },
    ]
    const kickUserAfters = [
        { text: 'settings.forms.never', value: 0 },
        { text: 'settings.forms.on_otp_copy', value: -1 },
        { text: 'settings.forms.1_minutes', value: 1 },
        { text: 'settings.forms.5_minutes', value: 5 },
        { text: 'settings.forms.10_minutes', value: 10 },
        { text: 'settings.forms.15_minutes', value: 15 },
        { text: 'settings.forms.30_minutes', value: 30 },
        { text: 'settings.forms.1_hour', value: 60 },
        { text: 'settings.forms.1_day', value: 1440 }, 
    ]
    const groups = [
        { text: 'groups.no_group', value: 0 },
        { text: 'groups.active_group', value: -1 },
    ]
    const captureModes = [
        { text: 'settings.forms.livescan', value: 'livescan' },
        { text: 'settings.forms.upload', value: 'upload' },
        { text: 'settings.forms.advanced_form', value: 'advancedForm' },
    ]
    const getOtpTriggers = [
        { text: 'settings.forms.otp_generation_on_request', value: true, legend: 'settings.forms.otp_generation_on_request_legend', title: 'settings.forms.otp_generation_on_request_title' },
        { text: 'settings.forms.otp_generation_on_home', value: false, legend: 'settings.forms.otp_generation_on_home_legend', title: 'settings.forms.otp_generation_on_home_title' },
    ]

    const langs = computed(() => {
        let locales = [{
            text: 'languages.browser_preference',
            value: 'browser'
        }];

        for (const locale of $2fauth.langs) {
            locales.push({
                text: 'languages.' + locale,
                value: locale
            })
        }
        return locales
    })

    user.$subscribe((mutation) => {
        userPreferenceService.update(mutation.events.key, mutation.events.newValue).then(response => {
            useNotifyStore().info({ type: 'is-success', text: trans('settings.forms.setting_saved') })
            
            if(mutation.events.key === 'lang' && getActiveLanguage() !== mutation.events.newValue) {
                user.applyLanguage()
            }
            else if(mutation.events.key === 'theme') {
                user.applyTheme()
            }
        })
    })

    appSettings.$subscribe((mutation) => {
        appSettingService.update(mutation.events.key, mutation.events.newValue).then(response => {
            useNotifyStore().info({ type: 'is-success', text: trans('settings.forms.setting_saved') })
        })
    })

    onMounted(() => {
        groupService.getAll().then(response => {
            response.data.forEach((data) => {
                if( data.id >0 ) {
                    groups.push({
                        text: data.name,
                        value: data.id
                    })
                }
            })
        })
    })

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('settings.')) {
            notify.clear()
        }
    })
</script>

<template>
    <div>
        <SettingTabs activeTab="settings.options"></SettingTabs>
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <!-- <input type="hidden" name="isReady" id="isReady" :value="isReady" /> -->
                    <!-- user preferences -->
                    <div class="block">
                        <h4 class="title is-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                        <!-- Language -->
                        <FormSelect v-model="user.preferences.lang" :options="langs" fieldName="lang" label="settings.forms.language.label" help="settings.forms.language.help" />
                        <div class="field help">
                            {{ $t('settings.forms.some_translation_are_missing') }}
                            <a class="ml-2" href="https://crowdin.com/project/2fauth">
                                {{ $t('settings.forms.help_translate_2fauth') }}
                                <FontAwesomeIcon :icon="['fas', 'external-link-alt']" />
                            </a>
                        </div>
                        <!-- display mode -->
                        <FormToggle v-model="user.preferences.displayMode" :choices="layouts" fieldName="displayMode" label="settings.forms.display_mode.label" help="settings.forms.display_mode.help"/>
                        <!-- theme -->
                        <FormToggle v-model="user.preferences.theme" :choices="themes" fieldName="theme" label="settings.forms.theme.label" help="settings.forms.theme.help"/>
                        <!-- show icon -->
                        <FormCheckbox v-model="user.preferences.showAccountsIcons" fieldName="showAccountsIcons" label="settings.forms.show_accounts_icons.label" help="settings.forms.show_accounts_icons.help" />
                        <!-- Official icons -->
                        <FormCheckbox v-model="user.preferences.getOfficialIcons" fieldName="getOfficialIcons" label="settings.forms.get_official_icons.label" help="settings.forms.get_official_icons.help" />
                        <!-- password format -->
                        <FormCheckbox v-model="user.preferences.formatPassword" fieldName="formatPassword" label="settings.forms.password_format.label" help="settings.forms.password_format.help" />
                        <FormToggle v-model="user.preferences.formatPasswordBy" :choices="passwordFormats" fieldName="formatPasswordBy" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('groups.groups') }}</h4>
                        <!-- default group -->
                        <FormSelect v-model="user.preferences.defaultGroup" :options="groups" fieldName="defaultGroup" label="settings.forms.default_group.label" help="settings.forms.default_group.help" />
                        <!-- retain active group -->
                        <FormCheckbox v-model="user.preferences.rememberActiveGroup" fieldName="rememberActiveGroup" label="settings.forms.remember_active_group.label" help="settings.forms.remember_active_group.help" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                        <!-- auto lock -->
                        <FormSelect v-model="user.preferences.kickUserAfter" :options="kickUserAfters" fieldName="kickUserAfter" label="settings.forms.auto_lock.label" help="settings.forms.auto_lock.help" />
                        <!-- get OTP on request -->
                        <FormToggle v-model="user.preferences.getOtpOnRequest" :choices="getOtpTriggers" fieldName="getOtpOnRequest" label="settings.forms.otp_generation.label" help="settings.forms.otp_generation.help"/>
                        <!-- otp as dot -->
                        <FormCheckbox v-model="user.preferences.showOtpAsDot" fieldName="showOtpAsDot" label="settings.forms.show_otp_as_dot.label" help="settings.forms.show_otp_as_dot.help" />
                        <!-- close otp on copy -->
                        <FormCheckbox v-model="user.preferences.closeOtpOnCopy" fieldName="closeOtpOnCopy" label="settings.forms.close_otp_on_copy.label" help="settings.forms.close_otp_on_copy.help" :disabled="!user.preferences.getOtpOnRequest" />
                        <!-- copy otp on get -->
                        <FormCheckbox v-model="user.preferences.copyOtpOnDisplay" fieldName="copyOtpOnDisplay" label="settings.forms.copy_otp_on_display.label" help="settings.forms.copy_otp_on_display.help" :disabled="!user.preferences.getOtpOnRequest" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.data_input') }}</h4>
                        <!-- basic qrcode -->
                        <FormCheckbox v-model="user.preferences.useBasicQrcodeReader" fieldName="useBasicQrcodeReader" label="settings.forms.use_basic_qrcode_reader.label" help="settings.forms.use_basic_qrcode_reader.help" />
                        <!-- direct capture -->
                        <FormCheckbox v-model="user.preferences.useDirectCapture" fieldName="useDirectCapture" label="settings.forms.useDirectCapture.label" help="settings.forms.useDirectCapture.help" />
                        <!-- default capture mode -->
                        <FormSelect v-model="user.preferences.defaultCaptureMode" :options="captureModes" fieldName="defaultCaptureMode" label="settings.forms.defaultCaptureMode.label" help="settings.forms.defaultCaptureMode.help" />
                    </div>
                    <!-- Admin settings -->
                    <div v-if="user.isAdmin">
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.administration') }}</h4>
                        <div class="is-size-7-mobile block" v-html="$t('settings.administration_legend')"></div>
                        <!-- Check for update -->
                        <FormCheckbox v-model="appSettings.checkForUpdate" fieldName="checkForUpdate" label="commons.check_for_update" help="commons.check_for_update_help" />
                        <VersionChecker />
                        <!-- protect db -->
                        <FormCheckbox v-model="appSettings.useEncryption" fieldName="useEncryption" label="settings.forms.use_encryption.label" help="settings.forms.use_encryption.help" />
                        <!-- disable registration -->
                        <FormCheckbox v-model="appSettings.disableRegistration" fieldName="disableRegistration" label="settings.forms.disable_registration.label" help="settings.forms.disable_registration.help" />
                    </div>
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <!-- Close button -->
            <p class="control">
                <UseColorMode v-slot="{ mode }">
                    <RouterLink
                        id="btnClose"
                        :to="{ name: returnTo }"
                        class="button is-rounded"
                        :class="{'is-dark' : mode === 'dark'}"
                        tabindex="0"
                        role="button"
                        :aria-label="$t('commons.close_the_x_page', {pagetitle: $route.meta.title})">
                        {{ $t('commons.close') }}
                    </RouterLink>
                </UseColorMode>
            </p>
        </VueFooter>
    </div>
</template>