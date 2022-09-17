<template>
    <form-wrapper :title="$t('settings.forms.new_token')">
        <form @submit.prevent="generatePAToken" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('commons.name')" autofocus />
            <form-buttons
                :submitId="'btnGenerateToken'"
                :isBusy="form.isBusy"
                :caption="$t('commons.generate')"
                :showCancelButton="true"
                cancelLandingView="settings.oauth.tokens" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data() {
            return {
                form: new Form({
                    name: ''
                })
            }
        },

        methods: {

            async generatePAToken() {

                const { data } = await this.form.post('/oauth/personal-access-tokens')

                if( this.form.errors.any() === false ) {
                    this.$router.push({ name: 'settings.oauth.tokens', params: { accessToken: data.accessToken, token_id: data.token.id } });
                }

            },
            
        },

    }
</script>