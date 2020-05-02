<template>
    <form-wrapper :fail="fail" :success="success">
        <div class="tags has-addons">
            <span class="tag is-dark">2FAuth</span>
            <span class="tag is-info">v{{ $root.appVersion }}</span>
        </div>
        <form @submit.prevent="handleSubmit" @change="handleSubmit" @keydown="form.onKeydown($event)">
            <form-select :options="langs" :form="form" fieldName="lang" :label="$t('settings.forms.language.label')"  :help="$t('settings.forms.language.help')" />
            <form-select :options="layouts" :form="form" fieldName="displayMode" :label="$t('settings.forms.display_mode.label')" :help="$t('settings.forms.display_mode.help')" />
            <form-switch :form="form" fieldName="showTokenAsDot" :label="$t('settings.forms.show_token_as_dot.label')" :help="$t('settings.forms.show_token_as_dot.help')" />
            <form-switch :form="form" fieldName="closeTokenOnCopy" :label="$t('settings.forms.close_token_on_copy.label')" :help="$t('settings.forms.close_token_on_copy.help')" />
            <form-switch :form="form" fieldName="useBasicQrcodeReader" :label="$t('settings.forms.use_basic_qrcode_reader.label')" :help="$t('settings.forms.use_basic_qrcode_reader.help')" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                success: '',
                fail: '',
                form: new Form({
                    lang: this.$root.$i18n.locale,
                    showTokenAsDot: this.$root.appSettings.showTokenAsDot,
                    closeTokenOnCopy: this.$root.appSettings.closeTokenOnCopy,
                    useBasicQrcodeReader: this.$root.appSettings.useBasicQrcodeReader,
                    displayMode: this.$root.appSettings.displayMode,
                }),
                langs: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
                ],
                layouts: [
                    { text: this.$t('settings.forms.grid'), value: 'grid' },
                    { text: this.$t('settings.forms.list'), value: 'list' },
                ]
            }
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.fail = ''
                this.success = ''

                this.form.post('/api/settings/options', {returnError: true})
                .then(response => {

                    this.success = response.data.message

                    if(response.data.settings.lang !== this.$root.$i18n.locale) {
                        this.$router.go()
                    }
                    else {
                        this.$root.appSettings = response.data.settings
                    }
                })
                .catch(error => {
                    
                    this.fail = error.response.data.message
                });
            }
        },
    }
</script>