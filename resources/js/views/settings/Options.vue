<script setup>
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import userService from '@/services/userService'
    import { useUserStore } from '@/stores/user'
    import { useGroups } from '@/stores/groups'
    import { useNotifyStore } from '@/stores/notify'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { timezones } from './timezones'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const groups = useGroups()
    const notify = useNotifyStore()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const layouts = [
        { text: 'settings.forms.grid', value: 'grid', icon: 'Grid3X3' },
        { text: 'settings.forms.list', value: 'list', icon: 'List' },
    ]
    const themes = [
        { text: 'settings.forms.light', value: 'light', icon: 'Sun' },
        { text: 'settings.forms.dark', value: 'dark', icon: 'Moon' },
        { text: 'settings.forms.automatic', value: 'system', icon: 'MonitorCheck' },
    ]
    const iconCollections = [
        { text: 'selfh.st', value: 'selfh', url: 'https://selfh.st/icons/', defaultVariant: 'regular' },
        { text: 'dashboardicons.com', value: 'dashboardicons', url: 'https://dashboardicons.com/', defaultVariant: 'regular' },
        { text: '2fa.directory', value: 'tfa', url: 'https://2fa.directory/', defaultVariant: 'regular' },
    ]
    const iconCollectionVariants = {
        selfh: [
            { text: 'commons.regular', value: 'regular' },
            { text: 'settings.forms.light', value: 'light' },
            { text: 'settings.forms.dark', value: 'dark' },
        ],
        dashboardicons: [
            { text: 'commons.regular', value: 'regular' },
            { text: 'settings.forms.light', value: 'light' },
            { text: 'settings.forms.dark', value: 'dark' },
        ],
        tfa: [
            { text: 'commons.regular', value: 'regular' },
        ],
    }
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
    const autoCloseTimeout = [
        { text: 'settings.forms.never', value: 0 },
        { text: 'settings.forms.1_minutes', value: 1 },
        { text: 'settings.forms.2_minutes', value: 2 },
        { text: 'settings.forms.5_minutes', value: 5 },
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

    const iconCollectionUrl = computed(() => {
        return iconCollections.find(({ value }) => value === user.preferences.iconCollection)?.url
    })

    const iconCollectionDomain = computed(() => {
        return iconCollections.find(({ value }) => value === user.preferences.iconCollection)?.text
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

    /**
     * Saves the iconCollection preference on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function saveIconCollection(value) {
        savePreference('iconCollection', value)

        if (! Object.prototype.hasOwnProperty.call(iconCollectionVariants, value)) {
            if (user.preferences.iconVariant != 'regular') {
                user.preferences.iconVariant = 'regular'
                userService.updatePreference('iconVariant', user.preferences.iconVariant)
            }
        }
        else {
            if (iconCollectionVariants[value].find((variant) => variant.value == user.preferences.iconVariant) == undefined) {
                user.preferences.iconVariant = iconCollections.find((collection) => collection.value == value).defaultVariant
                userService.updatePreference('iconVariant', user.preferences.iconVariant)
            }
        }
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
                        <div v-if="appSettings.lockedPreferences.length > 0" class="notification is-warning">
                            {{ $t('settings.settings_managed_by_administrator') }}
                        </div>
                        <!-- Language -->
                        <FormSelect v-model="user.preferences.lang" @update:model-value="val => savePreference('lang', val)" :options="langs" fieldName="lang" :isLocked="appSettings.lockedPreferences.includes('lang')" label="settings.forms.language.label" help="settings.forms.language.help" />
                        <div class="field help">
                            {{ $t('settings.forms.some_translation_are_missing') }}
                            <a class="ml-2" href="https://crowdin.com/project/2fauth">
                                {{ $t('settings.forms.help_translate_2fauth') }}
                                <FontAwesomeIcon :icon="['fas', 'external-link-alt']" />
                            </a>
                        </div>
                        <!-- timezone -->
                        <FormSelect v-model="user.preferences.timezone" @update:model-value="val => savePreference('timezone', val)" :options="timezones" fieldName="timezone" :isLocked="appSettings.lockedPreferences.includes('timezone')" label="settings.forms.timezone.label" help="settings.forms.timezone.help" />
                        <!-- display mode -->
                        <FormToggle v-model="user.preferences.displayMode" @update:model-value="val => savePreference('displayMode', val)" :choices="layouts" fieldName="displayMode" :isLocked="appSettings.lockedPreferences.includes('displayMode')" label="settings.forms.display_mode.label" help="settings.forms.display_mode.help" />
                        <!-- theme -->
                        <FormToggle v-model="user.preferences.theme" @update:model-value="val => savePreference('theme', val)" :choices="themes" fieldName="theme" :isLocked="appSettings.lockedPreferences.includes('theme')" label="settings.forms.theme.label" help="settings.forms.theme.help" />
                        <!-- show icon -->
                        <FormCheckbox v-model="user.preferences.showAccountsIcons" @update:model-value="val => savePreference('showAccountsIcons', val)" fieldName="showAccountsIcons" :isLocked="appSettings.lockedPreferences.includes('showAccountsIcons')" label="settings.forms.show_accounts_icons.label" help="settings.forms.show_accounts_icons.help" />
                        <!-- Official icons -->
                        <FormCheckbox v-model="user.preferences.getOfficialIcons" @update:model-value="val => savePreference('getOfficialIcons', val)" fieldName="getOfficialIcons" :isLocked="appSettings.lockedPreferences.includes('getOfficialIcons')" label="settings.forms.get_official_icons.label" help="settings.forms.get_official_icons.help" />
                        <!-- icon collections -->
                        <FormSelect v-model="user.preferences.iconCollection" @update:model-value="val => saveIconCollection(val)" :options="iconCollections" fieldName="iconCollection" :isLocked="appSettings.lockedPreferences.includes('iconCollection')" :isDisabled="!user.preferences.getOfficialIcons" label="settings.forms.icon_collection.label" help="settings.forms.icon_collection.help" :isIndented="true">
                            <a class="button is-ghost" :href="iconCollectionUrl" target="_blank" :title="$t('commons.visit_x', { website: iconCollectionDomain})">
                                <FontAwesomeIcon :icon="['fas', 'external-link-alt']" />
                            </a>
                        </FormSelect>
                        <!-- icon variant -->
                        <FormSelect v-model="user.preferences.iconVariant" @update:model-value="val => savePreference('iconVariant', val)" :options="iconCollectionVariants[user.preferences.iconCollection]" fieldName="iconVariant" :isLocked="appSettings.lockedPreferences.includes('iconVariant')" :isDisabled="!user.preferences.getOfficialIcons" label="settings.forms.icon_variant.label" help="settings.forms.icon_variant.help" :isIndented="true" />
                        <!-- icon variant strict fetch -->
                        <FormCheckbox v-model="user.preferences.iconVariantStrictFetch" @update:model-value="val => savePreference('iconVariantStrictFetch', val)" fieldName="iconVariantStrictFetch" :isLocked="appSettings.lockedPreferences.includes('iconVariantStrictFetch')" :isDisabled="user.preferences.iconVariant == 'regular'" label="settings.forms.icon_variant_strict_fetch.label" help="settings.forms.icon_variant_strict_fetch.help" :isIndented="true" />
                        <!-- password format -->
                        <FormCheckbox v-model="user.preferences.formatPassword" @update:model-value="val => savePreference('formatPassword', val)" fieldName="formatPassword" :isLocked="appSettings.lockedPreferences.includes('formatPassword')" label="settings.forms.password_format.label" help="settings.forms.password_format.help" />
                        <FormToggle v-model="user.preferences.formatPasswordBy" @update:model-value="val => savePreference('formatPasswordBy', val)" :choices="passwordFormats" fieldName="formatPasswordBy" :isLocked="appSettings.lockedPreferences.includes('formatPasswordBy')" :isDisabled="!user.preferences.formatPassword" />
                        <!-- clear search on copy -->
                        <FormCheckbox v-model="user.preferences.clearSearchOnCopy" @update:model-value="val => savePreference('clearSearchOnCopy', val)" fieldName="clearSearchOnCopy" :isLocked="appSettings.lockedPreferences.includes('clearSearchOnCopy')" label="settings.forms.clear_search_on_copy.label" help="settings.forms.clear_search_on_copy.help" />
                        <!-- sort case sensitive -->
                        <FormCheckbox v-model="user.preferences.sortCaseSensitive" @update:model-value="val => savePreference('sortCaseSensitive', val)" fieldName="sortCaseSensitive" :isLocked="appSettings.lockedPreferences.includes('sortCaseSensitive')" label="settings.forms.sort_case_sensitive.label" help="settings.forms.sort_case_sensitive.help" />
                        <!-- show email in footer -->
                        <FormCheckbox v-model="user.preferences.showEmailInFooter" @update:model-value="val => savePreference('showEmailInFooter', val)" fieldName="showEmailInFooter" :isLocked="appSettings.lockedPreferences.includes('showEmailInFooter')" label="settings.forms.show_email_in_footer.label" help="settings.forms.show_email_in_footer.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('groups.groups') }}</h4>
                        <!-- default group -->
                        <FormSelect v-model="user.preferences.defaultGroup" @update:model-value="val => savePreference('defaultGroup', val)" :options="groupsList" fieldName="defaultGroup" label="settings.forms.default_group.label" help="settings.forms.default_group.help" />
                        <!-- retain active group -->
                        <FormCheckbox v-model="user.preferences.rememberActiveGroup" @update:model-value="val => savePreference('rememberActiveGroup', val)" fieldName="rememberActiveGroup" :isLocked="appSettings.lockedPreferences.includes('rememberActiveGroup')" label="settings.forms.remember_active_group.label" help="settings.forms.remember_active_group.help" />
                        <!-- always return to default group after copying -->
                        <FormCheckbox v-model="user.preferences.viewDefaultGroupOnCopy" @update:model-value="val => savePreference('viewDefaultGroupOnCopy', val)" fieldName="viewDefaultGroupOnCopy" :isLocked="appSettings.lockedPreferences.includes('viewDefaultGroupOnCopy')" label="settings.forms.view_default_group_on_copy.label" help="settings.forms.view_default_group_on_copy.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                        <!-- auto lock -->
                        <FormSelect v-model="user.preferences.kickUserAfter" @update:model-value="val => savePreference('kickUserAfter', val)" :options="kickUserAfters" fieldName="kickUserAfter" :isLocked="appSettings.lockedPreferences.includes('kickUserAfter')" label="settings.forms.auto_lock.label" help="settings.forms.auto_lock.help" />
                        <!-- get OTP on request -->
                        <FormToggle v-model="user.preferences.getOtpOnRequest" @update:model-value="val => savePreference('getOtpOnRequest', val)" :choices="getOtpTriggers" fieldName="getOtpOnRequest" :isLocked="appSettings.lockedPreferences.includes('getOtpOnRequest')" label="settings.forms.otp_generation.label" help="settings.forms.otp_generation.help"/>
                            <!-- close otp on copy -->
                            <FormCheckbox v-model="user.preferences.closeOtpOnCopy" @update:model-value="val => savePreference('closeOtpOnCopy', val)" fieldName="closeOtpOnCopy" :isLocked="appSettings.lockedPreferences.includes('closeOtpOnCopy')" :isDisabled="!user.preferences.getOtpOnRequest" label="settings.forms.close_otp_on_copy.label" help="settings.forms.close_otp_on_copy.help" :isIndented="true" />
                            <!-- auto-close timeout -->
                            <FormSelect v-model="user.preferences.autoCloseTimeout" @update:model-value="val => savePreference('autoCloseTimeout', val)" :options="autoCloseTimeout" fieldName="autoCloseTimeout" :isLocked="appSettings.lockedPreferences.includes('autoCloseTimeout')" :isDisabled="!user.preferences.getOtpOnRequest" label="settings.forms.auto_close_timeout.label" help="settings.forms.auto_close_timeout.help" :isIndented="true" />
                            <!-- clear search on copy -->
                            <FormCheckbox v-model="user.preferences.copyOtpOnDisplay" @update:model-value="val => savePreference('copyOtpOnDisplay', val)" fieldName="copyOtpOnDisplay" :isLocked="appSettings.lockedPreferences.includes('copyOtpOnDisplay')" :isDisabled="!user.preferences.getOtpOnRequest" label="settings.forms.copy_otp_on_display.label" help="settings.forms.copy_otp_on_display.help" :isIndented="true" />
                        <!-- otp as dot -->
                        <FormCheckbox v-model="user.preferences.showOtpAsDot" @update:model-value="val => savePreference('showOtpAsDot', val)" fieldName="showOtpAsDot" :isLocked="appSettings.lockedPreferences.includes('showOtpAsDot')" label="settings.forms.show_otp_as_dot.label" help="settings.forms.show_otp_as_dot.help" />
                            <!-- reveal dotted OTPs -->
                            <FormCheckbox v-model="user.preferences.revealDottedOTP" @update:model-value="val => savePreference('revealDottedOTP', val)" fieldName="revealDottedOTP" :isLocked="appSettings.lockedPreferences.includes('revealDottedOTP')" :isDisabled="!user.preferences.showOtpAsDot" label="settings.forms.reveal_dotted_otp.label" help="settings.forms.reveal_dotted_otp.help" :isIndented="true" />
                        <!-- show next OTP -->
                        <FormCheckbox v-model="user.preferences.showNextOtp" @update:model-value="val => savePreference('showNextOtp', val)" fieldName="showNextOtp" :isLocked="appSettings.lockedPreferences.includes('showNextOtp')" label="settings.forms.show_next_otp.label" help="settings.forms.show_next_otp.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.notifications') }}</h4>
                        <!-- on new device -->
                        <FormCheckbox v-model="user.preferences.notifyOnNewAuthDevice" @update:model-value="val => savePreference('notifyOnNewAuthDevice', val)" fieldName="notifyOnNewAuthDevice" :isLocked="appSettings.lockedPreferences.includes('notifyOnNewAuthDevice')" label="settings.forms.notify_on_new_auth_device.label" help="settings.forms.notify_on_new_auth_device.help" />
                        <!-- on failed login -->
                        <FormCheckbox v-model="user.preferences.notifyOnFailedLogin" @update:model-value="val => savePreference('notifyOnFailedLogin', val)" fieldName="notifyOnFailedLogin" :isLocked="appSettings.lockedPreferences.includes('notifyOnFailedLogin')" label="settings.forms.notify_on_failed_login.label" help="settings.forms.notify_on_failed_login.help" />
                            
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.data_input') }}</h4>
                        <!-- auto-save QrCoded account -->
                        <FormCheckbox v-model="user.preferences.AutoSaveQrcodedAccount" @update:model-value="val => savePreference('AutoSaveQrcodedAccount', val)" fieldName="AutoSaveQrcodedAccount" :isLocked="appSettings.lockedPreferences.includes('AutoSaveQrcodedAccount')" label="settings.forms.auto_save_qrcoded_account.label" help="settings.forms.auto_save_qrcoded_account.help" />
                        <!-- basic qrcode -->
                        <FormCheckbox v-model="user.preferences.useBasicQrcodeReader" @update:model-value="val => savePreference('useBasicQrcodeReader', val)" fieldName="useBasicQrcodeReader" :isLocked="appSettings.lockedPreferences.includes('useBasicQrcodeReader')" label="settings.forms.use_basic_qrcode_reader.label" help="settings.forms.use_basic_qrcode_reader.help" />
                        <!-- direct capture -->
                        <FormCheckbox v-model="user.preferences.useDirectCapture" @update:model-value="val => savePreference('useDirectCapture', val)" fieldName="useDirectCapture" :isLocked="appSettings.lockedPreferences.includes('useDirectCapture')" label="settings.forms.useDirectCapture.label" help="settings.forms.useDirectCapture.help" />
                        <!-- default capture mode -->
                        <FormSelect v-model="user.preferences.defaultCaptureMode" @update:model-value="val => savePreference('defaultCaptureMode', val)" :options="captureModes" fieldName="defaultCaptureMode" :isLocked="appSettings.lockedPreferences.includes('defaultCaptureMode')" :isDisabled="!user.preferences.useDirectCapture" label="settings.forms.defaultCaptureMode.label" help="settings.forms.defaultCaptureMode.help" :isIndented="true" />
                    </div>
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.settings.options')" />
        </VueFooter>
    </div>
</template>