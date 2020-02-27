<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-select :options="options" :form="form" fieldName="lang" :label="$t('settings.forms.language.label')"  :help="$t('settings.forms.language.help')" />
            <form-switch :form="form" fieldName="showTokenAsDot" :label="$t('settings.forms.show_token_as_dot.label')" :help="$t('settings.forms.show_token_as_dot.help')" />
            <form-buttons :isBusy="form.isBusy" :caption="$t('commons.save')" />
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
                    showTokenAsDot: Boolean(Number(appSettings.showTokenAsDot)),
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

                this.form.post('/api/settings', {returnError: true})
                .then(response => {
                    this.$router.go()
                })
                .catch(error => {
                    if( error.response.status === 400 ) {
                        this.fail = error.response.data.message
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>