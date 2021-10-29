<template>
    <div>
        <setting-tabs :activeTab="'settings.options'"></setting-tabs>
        <div class="options-tabs">
            <form-wrapper>
                <!-- <form @submit.prevent="handleSubmit" @change="handleSubmit" @keydown="form.onKeydown($event)"> -->
                <form>
                    <h4 class="title is-4 has-text-grey-light">{{ $t('settings.general') }}</h4>
                    <!-- Language -->
                    <form-select v-on:lang="saveSetting('lang', $event)" :options="langs" :form="form" fieldName="lang" :label="$t('settings.forms.language.label')" :help="$t('settings.forms.language.help')" />
                    <!-- display mode -->
                    <form-toggle v-on:displayMode="saveSetting('displayMode', $event)" :choices="layouts" :form="form" fieldName="displayMode" :label="$t('settings.forms.display_mode.label')" :help="$t('settings.forms.display_mode.help')" />
                    <!-- show icon -->
                    <form-checkbox v-on:showAccountsIcons="saveSetting('showAccountsIcons', $event)" :form="form" fieldName="showAccountsIcons" :label="$t('settings.forms.show_accounts_icons.label')" :help="$t('settings.forms.show_accounts_icons.help')" />

                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('groups.groups') }}</h4>
                    <!-- default group -->
                    <form-select v-on:defaultGroup="saveSetting('defaultGroup', $event)" :options="groups" :form="form" fieldName="defaultGroup" :label="$t('settings.forms.default_group.label')" :help="$t('settings.forms.default_group.help')" />
                    <!-- retain active group -->
                    <form-checkbox v-on:rememberActiveGroup="saveSetting('rememberActiveGroup', $event)" :form="form" fieldName="rememberActiveGroup" :label="$t('settings.forms.remember_active_group.label')" :help="$t('settings.forms.remember_active_group.help')" />

                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.security') }}</h4>
                    <!-- auto lock -->
                    <form-select v-on:kickUserAfter="saveSetting('kickUserAfter', $event)" :options="kickUserAfters" :form="form" fieldName="kickUserAfter" :label="$t('settings.forms.auto_lock.label')"  :help="$t('settings.forms.auto_lock.help')" />
                    <!-- protect db -->
                    <form-checkbox v-on:useEncryption="saveSetting('useEncryption', $event)" :form="form" fieldName="useEncryption" :label="$t('settings.forms.use_encryption.label')" :help="$t('settings.forms.use_encryption.help')" />
                    <!-- otp as dot -->
                    <form-checkbox v-on:showOtpAsDot="saveSetting('showOtpAsDot', $event)" :form="form" fieldName="showOtpAsDot" :label="$t('settings.forms.show_otp_as_dot.label')" :help="$t('settings.forms.show_otp_as_dot.help')" />
                    <!-- close otp on copy -->
                    <form-checkbox v-on:closeOtpOnCopy="saveSetting('closeOtpOnCopy', $event)" :form="form" fieldName="closeOtpOnCopy" :label="$t('settings.forms.close_otp_on_copy.label')" :help="$t('settings.forms.close_otp_on_copy.help')" />

                    <h4 class="title is-4 pt-4 has-text-grey-light">{{ $t('settings.data_input') }}</h4>
                    <!-- basic qrcode -->
                    <form-checkbox v-on:useBasicQrcodeReader="saveSetting('useBasicQrcodeReader', $event)" :form="form" fieldName="useBasicQrcodeReader" :label="$t('settings.forms.use_basic_qrcode_reader.label')" :help="$t('settings.forms.use_basic_qrcode_reader.help')" />
                    <!-- direct capture -->
                    <form-checkbox v-on:useDirectCapture="saveSetting('useDirectCapture', $event)" :form="form" fieldName="useDirectCapture" :label="$t('settings.forms.useDirectCapture.label')" :help="$t('settings.forms.useDirectCapture.help')" />
                    <!-- default capture mode -->
                    <form-select v-on:defaultCaptureMode="saveSetting('defaultCaptureMode', $event)" :options="captureModes" :form="form" fieldName="defaultCaptureMode" :label="$t('settings.forms.defaultCaptureMode.label')" :help="$t('settings.forms.defaultCaptureMode.help')" />
                </form>
            </form-wrapper>
        </div>
        <vue-footer :showButtons="true">
            <!-- Cancel button -->
            <p class="control">
                <a class="button is-dark is-rounded" @click.stop="exitSettings">
                    {{ $t('commons.close') }}
                </a>
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

    export default {
        data(){
            return {
                form: new Form({
                    lang: '',
                    showOtpAsDot: null,
                    closeOtpOnCopy: null,
                    useBasicQrcodeReader: null,
                    showAccountsIcons: null,
                    displayMode: '',
                    kickUserAfter: '',
                    useEncryption: null,
                    defaultGroup: '',
                    useDirectCapture: null,
                    defaultCaptureMode: '',
                    rememberActiveGroup: true,
                }),
                langs: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
                    { text: this.$t('languages.de'), value: 'de' },
                ],
                layouts: [
                    { text: this.$t('settings.forms.grid'), value: 'grid', icon: 'th' },
                    { text: this.$t('settings.forms.list'), value: 'list', icon: 'list' },
                ],
                kickUserAfters: [
                    { text: this.$t('settings.forms.never'), value: '0' },
                    { text: this.$t('settings.forms.on_otp_copy'), value: '-1' },
                    { text: this.$t('settings.forms.1_minutes'), value: '1' },
                    { text: this.$t('settings.forms.5_minutes'), value: '5' },
                    { text: this.$t('settings.forms.10_minutes'), value: '10' },
                    { text: this.$t('settings.forms.15_minutes'), value: '15' },
                    { text: this.$t('settings.forms.30_minutes'), value: '30' },
                    { text: this.$t('settings.forms.1_hour'), value: '60' },
                    { text: this.$t('settings.forms.1_day'), value: '1440' }, 
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
            }
        },

        async mounted() {
            const { data } = await this.form.get('/api/settings')

            this.form.fillWithKeyValueObject(data)
            this.form.lang = this.$root.$i18n.locale
            this.form.setOriginal()
            this.fetchGroups()
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()
                console.log(e)

                // this.form.post('/api/settings/options', {returnError: false})
                // .then(response => {

                //     this.$notify({ type: 'is-success', text: response.data.message })

                //     if(response.data.settings.lang !== this.$root.$i18n.locale) {
                //         this.$router.go()
                //     }
                //     else {
                //         this.$root.appSettings = response.data.settings
                //     }
                // });
            },

            saveSetting(settingName, event) {

                this.axios.put('/api/settings/' + settingName, { value: event }).then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('settings.forms.setting_saved') })

                    if(settingName === 'lang' && response.data.value !== this.$root.$i18n.locale) {
                        this.$router.go()
                    }
                    else {
                        this.$root.appSettings[response.data.key] = response.data.value
                    }
                })
            },

            fetchGroups() {

                this.axios.get('/api/groups').then(response => {
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