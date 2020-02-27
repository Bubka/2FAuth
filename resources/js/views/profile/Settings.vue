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
                    lang: 'fr'
                }),
                options: [
                    { text: this.$t('languages.en'), value: 'en' },
                    { text: this.$t('languages.fr'), value: 'fr' },
                ]
            }
        },

        methods : {

        },
    }
</script>