<template>
    <form-wrapper :fail="fail" :success="success">
        <div class="tags has-addons">
            <span class="tag is-dark">2FAuth</span>
            <span class="tag is-info">v{{ $appVersion }}</span>
        </div>
        <form @submit.prevent="handleSubmit" @change="handleSubmit" @keydown="form.onKeydown($event)">
            <form-select :options="options" :form="form" fieldName="lang" :label="$t('settings.forms.language.label')"  :help="$t('settings.forms.language.help')" />
            <form-switch :form="form" fieldName="showTokenAsDot" :label="$t('settings.forms.show_token_as_dot.label')" :help="$t('settings.forms.show_token_as_dot.help')" />
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
                    showTokenAsDot: this.$appSettings.showTokenAsDot,
                }),
                options: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
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
                        this.$appSettings = response.data.settings
                    }
                })
                .catch(error => {
                    
                    this.fail = error.response.data.message
                });
            }
        },
    }
</script>