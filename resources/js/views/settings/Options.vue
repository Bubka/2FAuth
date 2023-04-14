<template>
    <div>
        <setting-tabs :activeTab="'settings.options'"></setting-tabs>
        <div class="options-tabs">
            <form-wrapper>
                <form>
                    <!-- user preferences -->
                    <div class="block">
                        <h4 class="title is-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                        <!-- Language -->
                        <form-select v-on:lang="savePreference('lang', $event)" :options="langs" :form="preferencesForm" fieldName="lang" :label="$t('settings.forms.language.label')" :help="$t('settings.forms.language.help')" />
                        <div class="field help">
                            {{ $t('settings.forms.some_translation_are_missing') }}
                            <a class="ml-2" href="https://crowdin.com/project/2fauth">
                                {{ $t('settings.forms.help_translate_2fauth') }}
                                <font-awesome-icon :icon="['fas', 'external-link-alt']" />
                            </a>
                        </div>
                        <!-- display mode -->
                        <form-toggle v-on:displayMode="savePreference('displayMode', $event)" :choices="layouts" :form="preferencesForm" fieldName="displayMode" :label="$t('settings.forms.display_mode.label')" :help="$t('settings.forms.display_mode.help')" />
                        <!-- theme -->
                        <form-toggle v-on:theme="savePreference('theme', $event)" :choices="themes" :form="preferencesForm" fieldName="theme" :label="$t('settings.forms.theme.label')" :help="$t('settings.forms.theme.help')" />
                        <!-- show icon -->
                        <form-checkbox v-on:showAccountsIcons="savePreference('showAccountsIcons', $event)" :form="preferencesForm" fieldName="showAccountsIcons" :label="$t('settings.forms.show_accounts_icons.label')" :help="$t('settings.forms.show_accounts_icons.help')" />
                        <!-- Official icons -->
                        <form-checkbox v-on:getOfficialIcons="savePreference('getOfficialIcons', $event)" :form="preferencesForm" fieldName="getOfficialIcons" :label="$t('settings.forms.get_official_icons.label')" :help="$t('settings.forms.get_official_icons.help')" />
                        <!-- password format -->
                        <form-checkbox v-on:formatPassword="savePreference('formatPassword', $event)" :form="preferencesForm" fieldName="formatPassword" :label="$t('settings.forms.password_format.label')" :help="$t('settings.forms.password_format.help')" />
                        <form-toggle v-if="preferencesForm.formatPassword" v-on:formatPasswordBy="savePreference('formatPasswordBy', $event)" :choices="passwordFormats" :form="preferencesForm" fieldName="formatPasswordBy" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('groups.groups') }}</h4>
                        <!-- default group -->
                        <form-select v-on:defaultGroup="savePreference('defaultGroup', $event)" :options="groups" :form="preferencesForm" fieldName="defaultGroup" :label="$t('settings.forms.default_group.label')" :help="$t('settings.forms.default_group.help')" />
                        <!-- retain active group -->
                        <form-checkbox v-on:rememberActiveGroup="savePreference('rememberActiveGroup', $event)" :form="preferencesForm" fieldName="rememberActiveGroup" :label="$t('settings.forms.remember_active_group.label')" :help="$t('settings.forms.remember_active_group.help')" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                        <!-- auto lock -->
                        <form-select v-on:kickUserAfter="savePreference('kickUserAfter', $event)" :options="kickUserAfters" :form="preferencesForm" fieldName="kickUserAfter" :label="$t('settings.forms.auto_lock.label')"  :help="$t('settings.forms.auto_lock.help')" />
                        <!-- get OTP on request -->
                        <form-toggle v-on:getOtpOnRequest="savePreference('getOtpOnRequest', $event)" :choices="getOtpTriggers" :form="preferencesForm" fieldName="getOtpOnRequest" :label="$t('settings.forms.otp_generation.label')" :help="$t('settings.forms.otp_generation.help')" />
                        <!-- otp as dot -->
                        <form-checkbox v-on:showOtpAsDot="savePreference('showOtpAsDot', $event)" :form="preferencesForm" fieldName="showOtpAsDot" :label="$t('settings.forms.show_otp_as_dot.label')" :help="$t('settings.forms.show_otp_as_dot.help')" />
                        <!-- close otp on copy -->
                        <form-checkbox v-on:closeOtpOnCopy="savePreference('closeOtpOnCopy', $event)" :form="preferencesForm" fieldName="closeOtpOnCopy" :label="$t('settings.forms.close_otp_on_copy.label')" :help="$t('settings.forms.close_otp_on_copy.help')" :disabled="!preferencesForm.getOtpOnRequest" />
                        <!-- copy otp on get -->
                        <form-checkbox v-on:copyOtpOnDisplay="savePreference('copyOtpOnDisplay', $event)" :form="preferencesForm" fieldName="copyOtpOnDisplay" :label="$t('settings.forms.copy_otp_on_display.label')" :help="$t('settings.forms.copy_otp_on_display.help')" :disabled="!preferencesForm.getOtpOnRequest" />

                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.data_input') }}</h4>
                        <!-- basic qrcode -->
                        <form-checkbox v-on:useBasicQrcodeReader="savePreference('useBasicQrcodeReader', $event)" :form="preferencesForm" fieldName="useBasicQrcodeReader" :label="$t('settings.forms.use_basic_qrcode_reader.label')" :help="$t('settings.forms.use_basic_qrcode_reader.help')" />
                        <!-- direct capture -->
                        <form-checkbox v-on:useDirectCapture="savePreference('useDirectCapture', $event)" :form="preferencesForm" fieldName="useDirectCapture" :label="$t('settings.forms.useDirectCapture.label')" :help="$t('settings.forms.useDirectCapture.help')" />
                        <!-- default capture mode -->
                        <form-select v-on:defaultCaptureMode="savePreference('defaultCaptureMode', $event)" :options="captureModes" :form="preferencesForm" fieldName="defaultCaptureMode" :label="$t('settings.forms.defaultCaptureMode.label')" :help="$t('settings.forms.defaultCaptureMode.help')" />
                    </div>
                    <!-- Admin settings -->
                    <div v-if="settingsForm">
                        <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.administration') }}</h4>
                        <div class="is-size-7-mobile block" v-html="$t('settings.administration_legend')"></div>
                        <!-- Check for update -->
                        <form-checkbox v-on:checkForUpdate="saveSetting('checkForUpdate', $event)" :form="settingsForm" fieldName="checkForUpdate" :label="$t('commons.check_for_update')" :help="$t('commons.check_for_update_help')" />
                        <version-checker></version-checker>
                        <!-- protect db -->
                        <form-checkbox v-on:useEncryption="saveSetting('useEncryption', $event)" :form="settingsForm" fieldName="useEncryption" :label="$t('settings.forms.use_encryption.label')" :help="$t('settings.forms.use_encryption.help')" />
                        <!-- disable registration -->
                        <form-checkbox v-on:disableRegistration="saveSetting('disableRegistration', $event)" :form="settingsForm" fieldName="disableRegistration" :label="$t('settings.forms.disable_registration.label')" :help="$t('settings.forms.disable_registration.help')" />
                    </div>
                </form>
            </form-wrapper>
        </div>
        <vue-footer :showButtons="true">
            <!-- Cancel button -->
            <p class="control">
                <button class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}" @click.stop="exitSettings">
                    {{ $t('commons.close') }}
                </button>
            </p>
        </vue-footer>
    </div>
</template>

<script>

    /**
     *  Options view
     *  
     *  route: '/settings'
     *  
     *  Allow user to edit any option.
     *  
     *  This is the content of the Options tab set in views/settings/index.vue
     *  The view is a form that automatically post to backend every time a field is changed.
     *
     *  All changes are dynamically applied thanks to vue reacivity, except the lang one which force the
     *  page to reload.
     *
     *  inputs : Running options values are passed using the this.$root.appSettings var to feed the form
     */

    import Form from './../../components/Form'
    import VersionChecker from './../../components/VersionChecker'

    export default {
        data(){
            return {
                preferencesForm: new Form({
                    lang: '',
                    showOtpAsDot: null,
                    closeOtpOnCopy: null,
                    copyOtpOnDisplay: null,
                    useBasicQrcodeReader: null,
                    showAccountsIcons: null,
                    displayMode: '',
                    kickUserAfter: null,
                    defaultGroup: '',
                    useDirectCapture: null,
                    defaultCaptureMode: '',
                    rememberActiveGroup: null,
                    getOfficialIcons: null,
                    theme: '',
                    formatPassword: null,
                    formatPasswordBy: '',
                    getOtpOnRequest: null,
                }),
                settingsForm: null,
                settings: {
                    useEncryption: null,
                    checkForUpdate: null,
                    disableRegistration: null,
                },
                layouts: [
                    { text: this.$t('settings.forms.grid'), value: 'grid', icon: 'th' },
                    { text: this.$t('settings.forms.list'), value: 'list', icon: 'list' },
                ],
                themes: [
                    { text: this.$t('settings.forms.light'), value: 'light', icon: 'sun' },
                    { text: this.$t('settings.forms.dark'), value: 'dark', icon: 'moon' },
                    { text: this.$t('settings.forms.automatic'), value: 'system', icon: 'desktop' },
                ],
                passwordFormats: [
                    { text: '12 34 56', value: 2, legend: this.$t('settings.forms.pair'), title: this.$t('settings.forms.pair_legend') },
                    { text: '123 456', value: 3, legend: this.$t('settings.forms.trio'), title: this.$t('settings.forms.trio_legend') },
                    { text: '1234 5678', value: 0.5, legend: this.$t('settings.forms.half'), title: this.$t('settings.forms.half_legend') },
                ],
                kickUserAfters: [
                    { text: this.$t('settings.forms.never'), value: 0 },
                    { text: this.$t('settings.forms.on_otp_copy'), value: -1 },
                    { text: this.$t('settings.forms.1_minutes'), value: 1 },
                    { text: this.$t('settings.forms.5_minutes'), value: 5 },
                    { text: this.$t('settings.forms.10_minutes'), value: 10 },
                    { text: this.$t('settings.forms.15_minutes'), value: 15 },
                    { text: this.$t('settings.forms.30_minutes'), value: 30 },
                    { text: this.$t('settings.forms.1_hour'), value: 60 },
                    { text: this.$t('settings.forms.1_day'), value: 1440 }, 
                ],
                groups: [
                    { text: this.$t('groups.no_group'), value: 0 },
                    { text: this.$t('groups.active_group'), value: -1 },
                ],
                captureModes: [
                    { text: this.$t('settings.forms.livescan'), value: 'livescan' },
                    { text: this.$t('settings.forms.upload'), value: 'upload' },
                    { text: this.$t('settings.forms.advanced_form'), value: 'advancedForm' },
                ],
                getOtpTriggers: [
                    { text: this.$t('settings.forms.otp_generation_on_request'), value: true, legend: this.$t('settings.forms.otp_generation_on_request_legend'), title: this.$t('settings.forms.otp_generation_on_request_title') },
                    { text: this.$t('settings.forms.otp_generation_on_home'), value: false, legend: this.$t('settings.forms.otp_generation_on_home_legend'), title: this.$t('settings.forms.otp_generation_on_home_title') },
                ],
            }
        },

        components: {
            VersionChecker,
        },

        computed : {
            langs: function() {
                let locales = [{
                    text: this.$t('languages.browser_preference'),
                    value: 'browser'
                }];

                for (const locale of window.appLocales) {
                    locales.push({
                        text: this.$t('languages.' + locale),
                        value: locale
                    })
                }
                return locales
            }
        },

        async mounted() {

            const preferences = await this.preferencesForm.get('/api/v1/user/preferences')
            this.preferencesForm.fillWithKeyValueObject(preferences.data)
            this.preferencesForm.setOriginal()

            this.axios.get('/api/v1/settings', {returnError: true}).then(response => {
                this.settingsForm = new Form(this.settings)
                this.settingsForm.fillWithKeyValueObject(response.data)
                this.settingsForm.setOriginal()
            })
            .catch(error => {
                // no admin rights, we do not set the Settings form
            })

            this.fetchGroups()
        },

        methods : {

            savePreference(preferenceName, event) {

                this.axios.put('/api/v1/user/preferences/' + preferenceName, { value: event }).then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('settings.forms.setting_saved') })

                    if(preferenceName === 'lang' && response.data.value !== this.$root.$i18n.locale) {
                        this.$router.go()
                    }
                    else {
                        this.$root.userPreferences[response.data.key] = response.data.value

                        if(preferenceName === 'theme') {
                            this.setTheme(response.data.value)
                        }
                    }
                })
            },

            saveSetting(settingName, event) {

                this.axios.put('/api/v1/settings/' + settingName, { value: event }).then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('settings.forms.setting_saved') })

                    this.$root.appSettings[response.data.key] = response.data.value
                })
            },

            fetchGroups() {

                this.axios.get('/api/v1/groups').then(response => {
                    response.data.forEach((data) => {
                        if( data.id >0 ) {
                            this.groups.push({
                                text: data.name,
                                value: data.id
                            })
                        }
                    })
                })
            },

        },
    }
</script>