<script setup>
    import tabs from './tabs'
    import userService from '@/services/userService'
    import { useUserStore } from '@/stores/user'
    import { useGroups } from '@/stores/groups'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { timezones } from './timezones'
    import { useI18n } from 'vue-i18n'
    import { LucideExternalLink } from 'lucide-vue-next'

    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const groups = useGroups()
    const notify = useNotify()
    const appSettings = useAppSettingsStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const layouts = [
        { text: 'label.grid', value: 'grid', icon: 'Grid3X3' },
        { text: 'label.list', value: 'list', icon: 'List' },
    ]
    const themes = [
        { text: 'label.light', value: 'light', icon: 'Sun' },
        { text: 'label.dark', value: 'dark', icon: 'Moon' },
        { text: 'label.automatic', value: 'system', icon: 'MonitorCheck' },
    ]
    const iconCollections = [
        { text: 'selfh.st', value: 'selfh', url: 'https://selfh.st/icons/', defaultVariant: 'regular' },
        { text: 'dashboardicons.com', value: 'dashboardicons', url: 'https://dashboardicons.com/', defaultVariant: 'regular' },
        { text: '2fa.directory', value: 'tfa', url: 'https://2fa.directory/', defaultVariant: 'regular' },
    ]
    const iconCollectionVariants = {
        selfh: [
            { text: 'label.regular', value: 'regular' },
            { text: 'label.light', value: 'light' },
            { text: 'label.dark', value: 'dark' },
        ],
        dashboardicons: [
            { text: 'label.regular', value: 'regular' },
            { text: 'label.light', value: 'light' },
            { text: 'label.dark', value: 'dark' },
        ],
        tfa: [
            { text: 'label.regular', value: 'regular' },
        ],
    }
    const passwordFormats = [
        { text: 'label.pair_digit', value: 2, legend: 'label.pair', title: 'label.pair.legend' },
        { text: 'label.trio_digit', value: 3, legend: 'label.trio', title: 'label.trio.legend' },
        { text: 'label.half_digit', value: 0.5, legend: 'label.half', title: 'label.half.legend' },
    ]
    const kickUserAfters = [
        { text: 'label.never', value: 0 },
        { text: 'label.on_otp_copy', value: -1 },
        { text: 'label.1_minutes', value: 1 },
        { text: 'label.5_minutes', value: 5 },
        { text: 'label.10_minutes', value: 10 },
        { text: 'label.15_minutes', value: 15 },
        { text: 'label.30_minutes', value: 30 },
        { text: 'label.1_hour', value: 60 },
        { text: 'label.1_day', value: 1440 }, 
    ]
    const autoCloseTimeout = [
        { text: 'label.never', value: 0 },
        { text: 'label.1_minutes', value: 1 },
        { text: 'label.2_minutes', value: 2 },
        { text: 'label.5_minutes', value: 5 },
    ]
    const groupsList = ref([
        { text: 'label.no_group', value: 0 },
        { text: 'label.active_group', value: -1 },
    ])
    const captureModes = [
        { text: 'label.livescan', value: 'livescan' },
        { text: 'label.qr_code_upload', value: 'upload' },
        { text: 'label.advanced_form', value: 'advancedForm' },
    ]
    const getOtpTriggers = [
        { text: 'label.after_a_click_tap', value: true, legend: 'label.alone_in_its_own_view', title: 'label.alone_in_its_own_view.title' },
        { text: 'label.constantly', value: false, legend: 'label.all_of_them_on_home', title: 'label.all_of_them_on_home.title' },
    ]

    const langs = computed(() => {
        let locales = [{
            text: 'label.browser_preference',
            value: 'browser'
        }];

        for (const locale of $2fauth.langs) {
            locales.push({
                text: 'lang.' + locale,
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
        if (groups.items) {
            groups.items.forEach((group) => {
                if( group.id > 0 ) {
                    groupsList.value.push({
                        text: group.name,
                        value: group.id
                    })
                }
            })
        }

        user.refreshPreferences()
    })

    /**
     * Saves a preference on the backend
     * @param {string} preference 
     * @param {any} value 
     */
    function savePreference(preference, value) {
        userService.updatePreference(preference, value).then(response => {
            useNotify().success({ text: t('notification.setting_saved') })
            
            if(preference === 'lang') {
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
        <TabBar :tabs="tabs" :active-tab="'settings.options'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <form>
                    <!-- <input type="hidden" name="isReady" id="isReady" :value="isReady" /> -->
                    <!-- user preferences -->
                    <div class="block">
                        <h4 class="title is-4 has-text-grey-light">{{ $t('heading.general') }}</h4>
                        <div v-if="appSettings.lockedPreferences.length > 0" class="notification is-warning">
                            {{ $t('message.settings_managed_by_administrator') }}
                        </div>
                        <!-- Language -->
                        <FormSelect v-model="user.preferences.lang" @update:model-value="val => savePreference('lang', val)" :options="langs" fieldName="lang" :isLocked="appSettings.lockedPreferences.includes('lang')" label="field.language" help="field.language.help" />
                        <div class="field help">
                            {{ $t('message.some_translation_are_missing') }}
                            <a class="ml-2" href="https://crowdin.com/project/2fauth">
                                {{ $t('link.help_translate_2fauth') }}
                                <LucideExternalLink class="icon-size-1" />
                            </a>
                        </div>
                        <!-- timezone -->
                        <FormSelect v-model="user.preferences.timezone" @update:model-value="val => savePreference('timezone', val)" :options="timezones" fieldName="timezone" :isLocked="appSettings.lockedPreferences.includes('timezone')" label="field.timezone" help="field.timezone.help" />
                        <!-- display mode -->
                        <FormToggle v-model="user.preferences.displayMode" @update:model-value="val => savePreference('displayMode', val)" :choices="layouts" fieldName="displayMode" :isLocked="appSettings.lockedPreferences.includes('displayMode')" label="field.display_mode" help="field.display_mode.help" />
                        <!-- theme -->
                        <FormToggle v-model="user.preferences.theme" @update:model-value="val => savePreference('theme', val)" :choices="themes" fieldName="theme" :isLocked="appSettings.lockedPreferences.includes('theme')" label="field.theme" help="field.theme.help" />
                        <!-- show icon -->
                        <FormCheckbox v-model="user.preferences.showAccountsIcons" @update:model-value="val => savePreference('showAccountsIcons', val)" fieldName="showAccountsIcons" :isLocked="appSettings.lockedPreferences.includes('showAccountsIcons')" label="field.show_accounts_icons" help="field.show_accounts_icons.help" />
                        <!-- Official icons -->
                        <FormCheckbox v-model="user.preferences.getOfficialIcons" @update:model-value="val => savePreference('getOfficialIcons', val)" fieldName="getOfficialIcons" :isLocked="appSettings.lockedPreferences.includes('getOfficialIcons')" label="field.get_official_icons" help="field.get_official_icons.help" />
                        <!-- icon collections -->
                        <FormSelect v-model="user.preferences.iconCollection" @update:model-value="val => saveIconCollection(val)" :options="iconCollections" fieldName="iconCollection" :isLocked="appSettings.lockedPreferences.includes('iconCollection')" :isDisabled="!user.preferences.getOfficialIcons" label="field.icon_collection" help="field.icon_collection.help" :isIndented="true">
                            <a class="button is-ghost" :href="iconCollectionUrl" target="_blank" :title="$t('tooltip.visit_x', { website: iconCollectionDomain})">
                                <LucideExternalLink />
                            </a>
                        </FormSelect>
                        <!-- icon variant -->
                        <FormSelect v-model="user.preferences.iconVariant" @update:model-value="val => savePreference('iconVariant', val)" :options="iconCollectionVariants[user.preferences.iconCollection]" fieldName="iconVariant" :isLocked="appSettings.lockedPreferences.includes('iconVariant')" :isDisabled="!user.preferences.getOfficialIcons" label="field.icon_variant" help="field.icon_variant.help" :isIndented="true" />
                        <!-- icon variant strict fetch -->
                        <FormCheckbox v-model="user.preferences.iconVariantStrictFetch" @update:model-value="val => savePreference('iconVariantStrictFetch', val)" fieldName="iconVariantStrictFetch" :isLocked="appSettings.lockedPreferences.includes('iconVariantStrictFetch')" :isDisabled="user.preferences.iconVariant == 'regular'" label="field.icon_variant_strict_fetch" help="field.icon_variant_strict_fetch.help" :isIndented="true" />
                        <!-- password format -->
                        <FormCheckbox v-model="user.preferences.formatPassword" @update:model-value="val => savePreference('formatPassword', val)" fieldName="formatPassword" :isLocked="appSettings.lockedPreferences.includes('formatPassword')" label="field.password_format" help="field.password_format.help" />
                        <FormToggle v-model="user.preferences.formatPasswordBy" @update:model-value="val => savePreference('formatPasswordBy', val)" :choices="passwordFormats" fieldName="formatPasswordBy" :isLocked="appSettings.lockedPreferences.includes('formatPasswordBy')" :isDisabled="!user.preferences.formatPassword" />
                        <!-- clear search on copy -->
                        <FormCheckbox v-model="user.preferences.clearSearchOnCopy" @update:model-value="val => savePreference('clearSearchOnCopy', val)" fieldName="clearSearchOnCopy" :isLocked="appSettings.lockedPreferences.includes('clearSearchOnCopy')" label="field.clear_search_on_copy" help="field.clear_search_on_copy.help" />
                        <!-- sort case sensitive -->
                        <FormCheckbox v-model="user.preferences.sortCaseSensitive" @update:model-value="val => savePreference('sortCaseSensitive', val)" fieldName="sortCaseSensitive" :isLocked="appSettings.lockedPreferences.includes('sortCaseSensitive')" label="field.sort_case_sensitive" help="field.sort_case_sensitive.help" />
                        <!-- show email in footer -->
                        <FormCheckbox v-model="user.preferences.showEmailInFooter" @update:model-value="val => savePreference('showEmailInFooter', val)" fieldName="showEmailInFooter" :isLocked="appSettings.lockedPreferences.includes('showEmailInFooter')" label="field.show_email_in_footer" help="field.show_email_in_footer.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.groups') }}</h4>
                        <!-- default group -->
                        <FormSelect v-model="user.preferences.defaultGroup" @update:model-value="val => savePreference('defaultGroup', val)" :options="groupsList" fieldName="defaultGroup" label="field.default_group" help="field.default_group.help" />
                        <!-- retain active group -->
                        <FormCheckbox v-model="user.preferences.rememberActiveGroup" @update:model-value="val => savePreference('rememberActiveGroup', val)" fieldName="rememberActiveGroup" :isLocked="appSettings.lockedPreferences.includes('rememberActiveGroup')" label="field.remember_active_group" help="field.remember_active_group.help" />
                        <!-- always return to default group after copying -->
                        <FormCheckbox v-model="user.preferences.viewDefaultGroupOnCopy" @update:model-value="val => savePreference('viewDefaultGroupOnCopy', val)" fieldName="viewDefaultGroupOnCopy" :isLocked="appSettings.lockedPreferences.includes('viewDefaultGroupOnCopy')" label="field.view_default_group_on_copy" help="field.view_default_group_on_copy.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.security') }}</h4>
                        <!-- auto lock -->
                        <FormSelect v-model="user.preferences.kickUserAfter" @update:model-value="val => savePreference('kickUserAfter', val)" :options="kickUserAfters" fieldName="kickUserAfter" :isLocked="appSettings.lockedPreferences.includes('kickUserAfter')" label="field.auto_lock" help="field.auto_lock.help" />
                        <!-- get OTP on request -->
                        <FormToggle v-model="user.preferences.getOtpOnRequest" @update:model-value="val => savePreference('getOtpOnRequest', val)" :choices="getOtpTriggers" fieldName="getOtpOnRequest" :isLocked="appSettings.lockedPreferences.includes('getOtpOnRequest')" label="field.otp_generation" help="field.otp_generation.help"/>
                            <!-- close otp on copy -->
                            <FormCheckbox v-model="user.preferences.closeOtpOnCopy" @update:model-value="val => savePreference('closeOtpOnCopy', val)" fieldName="closeOtpOnCopy" :isLocked="appSettings.lockedPreferences.includes('closeOtpOnCopy')" :isDisabled="!user.preferences.getOtpOnRequest" label="field.close_otp_on_copy" help="field.close_otp_on_copy.help" :isIndented="true" />
                            <!-- auto-close timeout -->
                            <FormSelect v-model="user.preferences.autoCloseTimeout" @update:model-value="val => savePreference('autoCloseTimeout', val)" :options="autoCloseTimeout" fieldName="autoCloseTimeout" :isLocked="appSettings.lockedPreferences.includes('autoCloseTimeout')" :isDisabled="!user.preferences.getOtpOnRequest" label="field.auto_close_timeout" help="field.auto_close_timeout.help" :isIndented="true" />
                            <!-- clear search on copy -->
                            <FormCheckbox v-model="user.preferences.copyOtpOnDisplay" @update:model-value="val => savePreference('copyOtpOnDisplay', val)" fieldName="copyOtpOnDisplay" :isLocked="appSettings.lockedPreferences.includes('copyOtpOnDisplay')" :isDisabled="!user.preferences.getOtpOnRequest" label="field.copy_otp_on_display" help="field.copy_otp_on_display.help" :isIndented="true" />
                        <!-- otp as dot -->
                        <FormCheckbox v-model="user.preferences.showOtpAsDot" @update:model-value="val => savePreference('showOtpAsDot', val)" fieldName="showOtpAsDot" :isLocked="appSettings.lockedPreferences.includes('showOtpAsDot')" label="field.show_otp_as_dot" help="field.show_otp_as_dot.help" />
                            <!-- reveal dotted OTPs -->
                            <FormCheckbox v-model="user.preferences.revealDottedOTP" @update:model-value="val => savePreference('revealDottedOTP', val)" fieldName="revealDottedOTP" :isLocked="appSettings.lockedPreferences.includes('revealDottedOTP')" :isDisabled="!user.preferences.showOtpAsDot" label="field.reveal_dotted_otp" help="field.reveal_dotted_otp.help" :isIndented="true" />
                        <!-- show next OTP -->
                        <FormCheckbox v-model="user.preferences.showNextOtp" @update:model-value="val => savePreference('showNextOtp', val)" fieldName="showNextOtp" :isLocked="appSettings.lockedPreferences.includes('showNextOtp')" label="field.show_next_otp" help="field.show_next_otp.help" />
                        
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.notifications') }}</h4>
                        <!-- on new device -->
                        <FormCheckbox v-model="user.preferences.notifyOnNewAuthDevice" @update:model-value="val => savePreference('notifyOnNewAuthDevice', val)" fieldName="notifyOnNewAuthDevice" :isLocked="appSettings.lockedPreferences.includes('notifyOnNewAuthDevice')" label="field.notify_on_new_auth_device" help="field.notify_on_new_auth_device.help" />
                        <!-- on failed login -->
                        <FormCheckbox v-model="user.preferences.notifyOnFailedLogin" @update:model-value="val => savePreference('notifyOnFailedLogin', val)" fieldName="notifyOnFailedLogin" :isLocked="appSettings.lockedPreferences.includes('notifyOnFailedLogin')" label="field.notify_on_failed_login" help="field.notify_on_failed_login.help" />
                            
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('heading.data_input') }}</h4>
                        <!-- auto-save QrCoded account -->
                        <FormCheckbox v-model="user.preferences.AutoSaveQrcodedAccount" @update:model-value="val => savePreference('AutoSaveQrcodedAccount', val)" fieldName="AutoSaveQrcodedAccount" :isLocked="appSettings.lockedPreferences.includes('AutoSaveQrcodedAccount')" label="field.auto_save_qrcoded_account" help="field.auto_save_qrcoded_account.help" />
                        <!-- basic qrcode -->
                        <FormCheckbox v-model="user.preferences.useBasicQrcodeReader" @update:model-value="val => savePreference('useBasicQrcodeReader', val)" fieldName="useBasicQrcodeReader" :isLocked="appSettings.lockedPreferences.includes('useBasicQrcodeReader')" label="field.use_basic_qrcode_reader" help="field.use_basic_qrcode_reader.help" />
                        <!-- direct capture -->
                        <FormCheckbox v-model="user.preferences.useDirectCapture" @update:model-value="val => savePreference('useDirectCapture', val)" fieldName="useDirectCapture" :isLocked="appSettings.lockedPreferences.includes('useDirectCapture')" label="field.useDirectCapture" help="field.useDirectCapture.help" />
                        <!-- default capture mode -->
                        <FormSelect v-model="user.preferences.defaultCaptureMode" @update:model-value="val => savePreference('defaultCaptureMode', val)" :options="captureModes" fieldName="defaultCaptureMode" :isLocked="appSettings.lockedPreferences.includes('defaultCaptureMode')" :isDisabled="!user.preferences.useDirectCapture" label="field.defaultCaptureMode" help="field.defaultCaptureMode.help" :isIndented="true" />
                    </div>
                </form>
            </FormWrapper>
        </div>
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.settings.options')" />
            </template>
        </VueFooter>
    </div>
</template>