<template>
    <form-wrapper>
        <form @submit.prevent="handleSubmit" @change="handleSubmit" @keydown="form.onKeydown($event)">
            <h4 class="title is-4">{{ $t('settings.general') }}</h4>
            <!-- Language -->
            <form-select :options="langs" :form="form" fieldName="lang" :label="$t('settings.forms.language.label')"  :help="$t('settings.forms.language.help')" />
            <!-- display mode -->
            <form-toggle :choices="layouts" :form="form" fieldName="displayMode" :label="$t('settings.forms.display_mode.label')" :help="$t('settings.forms.display_mode.help')" />
            <!-- show icon -->
            <form-checkbox :form="form" fieldName="showAccountsIcons" :label="$t('settings.forms.show_accounts_icons.label')" :help="$t('settings.forms.show_accounts_icons.help')" />
            <!-- default group -->
            <form-select :options="groups" :form="form" fieldName="defaultGroup" :label="$t('settings.forms.default_group.label')" :help="$t('settings.forms.default_group.help')" />

            <h4 class="title is-4">{{ $t('settings.security') }}</h4>
            <!-- auto lock -->
            <form-select :options="kickUserAfters" :form="form" fieldName="kickUserAfter" :label="$t('settings.forms.auto_lock.label')"  :help="$t('settings.forms.auto_lock.help')" />
            <!-- protect db -->
            <form-checkbox :form="form" fieldName="useEncryption" :label="$t('settings.forms.use_encryption.label')" :help="$t('settings.forms.use_encryption.help')" />
            <!-- token as dot -->
            <form-checkbox :form="form" fieldName="showTokenAsDot" :label="$t('settings.forms.show_token_as_dot.label')" :help="$t('settings.forms.show_token_as_dot.help')" />
            <!-- close token on copy -->
            <form-checkbox :form="form" fieldName="closeTokenOnCopy" :label="$t('settings.forms.close_token_on_copy.label')" :help="$t('settings.forms.close_token_on_copy.help')" />

            <h4 class="title is-4">{{ $t('settings.advanced') }}</h4>
            <!-- basic qrcode -->
            <form-checkbox :form="form" fieldName="useBasicQrcodeReader" :label="$t('settings.forms.use_basic_qrcode_reader.label')" :help="$t('settings.forms.use_basic_qrcode_reader.help')" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                form: new Form({
                    lang: this.$root.$i18n.locale,
                    showTokenAsDot: this.$root.appSettings.showTokenAsDot,
                    closeTokenOnCopy: this.$root.appSettings.closeTokenOnCopy,
                    useBasicQrcodeReader: this.$root.appSettings.useBasicQrcodeReader,
                    showAccountsIcons: this.$root.appSettings.showAccountsIcons,
                    displayMode: this.$root.appSettings.displayMode,
                    kickUserAfter: this.$root.appSettings.kickUserAfter,
                    useEncryption: this.$root.appSettings.useEncryption,
                    defaultGroup: this.$root.appSettings.defaultGroup,
                }),
                langs: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
                ],
                layouts: [
                    { text: this.$t('settings.forms.grid'), value: 'grid', icon: 'th' },
                    { text: this.$t('settings.forms.list'), value: 'list', icon: 'list' },
                ],
                kickUserAfters: [
                    { text: this.$t('settings.forms.never'), value: '0' },
                    { text: this.$t('settings.forms.on_token_copy'), value: '-1' },
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
                ]
            }
        },

        mounted() {
            this.fetchGroups()
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('/api/settings/options', {returnError: false})
                .then(response => {

                    this.$notify({ type: 'is-success', text: response.data.message })

                    if(response.data.settings.lang !== this.$root.$i18n.locale) {
                        this.$router.go()
                    }
                    else {
                        this.$root.appSettings = response.data.settings
                    }
                });
            },

            fetchGroups() {

                this.axios.get('api/groups').then(response => {
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