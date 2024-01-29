<script setup>
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import userService from '@/services/userService'
    import { useUserStore } from '@/stores/user'
    import { useGroups } from '@/stores/groups'
    import { useNotifyStore } from '@/stores/notify'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const groups = useGroups()
    const notify = useNotifyStore()
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
    const groupsList = ref([
        { text: 'groups.no_group', value: 0 },
        { text: 'groups.active_group', value: -1 },
    ])
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

    onMounted(() => {
        groups.items.forEach((group) => {
            if( group.id > 0 ) {
                groupsList.value.push({
                    text: group.name,
                    value: group.id
                })
            }
        })

        user.refreshPreferences()
    })

    /**
     * Saves a preference on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function savePreference(preference, value) {
        userService.updatePreference(preference, value).then(response => {
            useNotifyStore().success({ type: 'is-success', text: trans('settings.forms.setting_saved') })
            
            if(preference === 'lang' && getActiveLanguage() !== value) {
                user.applyLanguage()
            }
            else if(preference === 'theme') {
                user.applyTheme()
            }
        })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('settings.')) {
            notify.clear()
        }
    })

</script>

<template>
    <div>
        <SettingTabs activeTab="settings.options" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <!-- <input type="hidden" name="isReady" id="isReady" :value="isReady" /> -->
                    <!-- user preferences -->
                    <div class="block">
                        <h4 class="title is-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                        <!-- Language -->
                        <FormSelect v-model="user.preferences.lang" @update:model-value="val => savePreference('lang', val)" :options="langs" fieldName="lang" label="settings.forms.language.label" help="settings.forms.language.help" />
                        <div class="field help">
                            {{ $t('settings.forms.some_translation_are_missing') }}
                            <a class="ml-2" href="https://crowdin.com/project/2fauth">
                                {{ $t('settings.forms.help_translate_2fauth') }}
                                <FontAwesomeIcon :icon="['fas', 'external-link-alt']" />
                            </a>
                        </div>
                        <!-- display mode -->
                        <FormToggle v-model="user.preferences.displayMode" @update:model-value="val => savePreference('displayMode', val)" :choices="layouts" fieldName="displayMode" label="settings.forms.display_mode.label" help="settings.forms.display_mode.help"/>
                        <!-- theme -->
                        <FormToggle v-model="user.preferences.theme" @update:model-value="val => savePreference('theme', val)" :choices="themes" fieldName="theme" label="settings.forms.theme.label" help="settings.forms.theme.help"/>
                        <!-- show icon -->
                        <FormCheckbox v-model="user.preferences.showAccountsIcons" @update:model-value="val => savePreference('showAccountsIcons', val)" fieldName="showAccountsIcons" label="settings.forms.show_accounts_icons.label" help="settings.forms.show_accounts_icons.help" />
                        <!-- Official icons -->
                        <FormCheckbox v-model="user.preferences.getOfficialIcons" @update:model-value="val => savePreference('getOfficialIcons', val)" fieldName="getOfficialIcons" label="settings.forms.get_official_icons.label" help="settings.forms.get_official_icons.help" />
                        <!-- password format -->
                        <FormCheckbox v-model="user.preferences.formatPassword" @update:model-value="val => savePreference('formatPassword', val)" fieldName="formatPassword" label="settings.forms.password_format.label" help="settings.forms.password_format.help" />
                        <FormToggle v-model="user.preferences.formatPasswordBy" @update:model-value="val => savePreference('formatPasswordBy', val)" :choices="passwordFormats" fieldName="formatPasswordBy" :isDisabled="!user.preferences.formatPassword" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('groups.groups') }}</h4>
                        <!-- default group -->
                        <FormSelect v-model="user.preferences.defaultGroup" @update:model-value="val => savePreference('defaultGroup', val)" :options="groupsList" fieldName="defaultGroup" label="settings.forms.default_group.label" help="settings.forms.default_group.help" />
                        <!-- retain active group -->
                        <FormCheckbox v-model="user.preferences.rememberActiveGroup" @update:model-value="val => savePreference('rememberActiveGroup', val)" fieldName="rememberActiveGroup" label="settings.forms.remember_active_group.label" help="settings.forms.remember_active_group.help" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                        <!-- auto lock -->
                        <FormSelect v-model="user.preferences.kickUserAfter" @update:model-value="val => savePreference('kickUserAfter', val)" :options="kickUserAfters" fieldName="kickUserAfter" label="settings.forms.auto_lock.label" help="settings.forms.auto_lock.help" />
                        <!-- get OTP on request -->
                        <FormToggle v-model="user.preferences.getOtpOnRequest" @update:model-value="val => savePreference('getOtpOnRequest', val)" :choices="getOtpTriggers" fieldName="getOtpOnRequest" label="settings.forms.otp_generation.label" help="settings.forms.otp_generation.help"/>
                            <!-- close otp on copy -->
                            <FormCheckbox v-model="user.preferences.closeOtpOnCopy" @update:model-value="val => savePreference('closeOtpOnCopy', val)" fieldName="closeOtpOnCopy" label="settings.forms.close_otp_on_copy.label" help="settings.forms.close_otp_on_copy.help" :isDisabled="!user.preferences.getOtpOnRequest" :isIndented="true" />
                            <!-- copy otp on get -->
                            <FormCheckbox v-model="user.preferences.copyOtpOnDisplay" @update:model-value="val => savePreference('copyOtpOnDisplay', val)" fieldName="copyOtpOnDisplay" label="settings.forms.copy_otp_on_display.label" help="settings.forms.copy_otp_on_display.help" :isDisabled="!user.preferences.getOtpOnRequest" :isIndented="true" />
                        <!-- otp as dot -->
                        <FormCheckbox v-model="user.preferences.showOtpAsDot" @update:model-value="val => savePreference('showOtpAsDot', val)" fieldName="showOtpAsDot" label="settings.forms.show_otp_as_dot.label" help="settings.forms.show_otp_as_dot.help" />
                            <!-- reveal dotted OTPs -->
                            <FormCheckbox v-model="user.preferences.revealDottedOTP" @update:model-value="val => savePreference('revealDottedOTP', val)" fieldName="revealDottedOTP" label="settings.forms.reveal_dotted_otp.label" help="settings.forms.reveal_dotted_otp.help" :isDisabled="!user.preferences.showOtpAsDot" :isIndented="true" />
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.data_input') }}</h4>
                        <!-- basic qrcode -->
                        <FormCheckbox v-model="user.preferences.useBasicQrcodeReader" @update:model-value="val => savePreference('useBasicQrcodeReader', val)" fieldName="useBasicQrcodeReader" label="settings.forms.use_basic_qrcode_reader.label" help="settings.forms.use_basic_qrcode_reader.help" />
                        <!-- direct capture -->
                        <FormCheckbox v-model="user.preferences.useDirectCapture" @update:model-value="val => savePreference('useDirectCapture', val)" fieldName="useDirectCapture" label="settings.forms.useDirectCapture.label" help="settings.forms.useDirectCapture.help" />
                        <!-- default capture mode -->
                        <FormSelect v-model="user.preferences.defaultCaptureMode" @update:model-value="val => savePreference('defaultCaptureMode', val)" :options="captureModes" fieldName="defaultCaptureMode" label="settings.forms.defaultCaptureMode.label" help="settings.forms.defaultCaptureMode.help" />
                    </div>
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
        </VueFooter>
    </div>
</template>