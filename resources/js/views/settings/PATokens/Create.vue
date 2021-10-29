<template>
    <form-wrapper :title="$t('settings.forms.new_token')">
        <form @submit.prevent="generatePAToken" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('commons.name')" autofocus />
            <div class="field is-grouped">
                <div class="control">
                    <v-button>{{ $t('commons.generate') }}</v-button>
                </div>
                <div class="control">
                    <button type="button" class="button is-text" @click="cancelGeneration">{{ $t('commons.cancel') }}</button>
                </div>
            </div>
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

                const { data } = await this.form.post('/api/oauth/personal-access-tokens')

                if( this.form.errors.any() === false ) {
                    this.$router.push({ name: 'settings.oauth', params: { accessToken: data.accessToken, token_id: data.token.id } });
                }

            },

            cancelGeneration: function() {

                this.$router.push({ name: 'settings.oauth' });
            },
            
        },

    }
</script>