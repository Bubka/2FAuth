<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-select :options="options" :form="form" fieldName="lang" :label="$t('settings.language')" />
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
                    lang: ''
                }),
                options: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
                ]
            }
        },

        async mounted() {

            const { data } = await this.axios.get('/api/settings')

            data.settings.forEach((setting) => {
                this.form[setting.key] = setting.value
            })
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