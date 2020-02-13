<template>
    <form-wrapper :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <div class="field">
                <label class="label">{{ $t('auth.forms.name') }}</label>
                <div class="control">
                    <input id="FirstSetting" type="text" class="input" v-model="form.FirstSetting" autofocus />
                </div>
                <field-error :form="form" field="FirstSetting" />
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <v-button :isLoading="form.isBusy" >{{ $t('commons.save') }}</v-button>
                </div>
            </div>
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