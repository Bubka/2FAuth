<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="FirstSetting" :label="$t('auth.forms.FirstSetting')" />
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
                    FirstSetting: ''
                })
            }
        },

        methods : {

        },
    }
</script>