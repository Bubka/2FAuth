<template>
    <form-wrapper :title="$t('auth.webauthn.rename_device')">
        <form @submit.prevent="updateCredential" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('commons.new_name')" autofocus />
            <form-buttons
                :submitId="'btnEditCredential'"
                :isBusy="form.isBusy"
                :caption="$t('commons.save')"
                :showCancelButton="true"
                cancelLandingView="settings.webauthn.devices" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../../components/Form'

    export default {
        data() {
            return {
                form: new Form({
                    name: this.name,
                })
            }
        },

        props: ['id', 'name'],

        methods: {

            async updateCredential() {

                await this.form.patch('/webauthn/credentials/' + this.id + '/name')

                if( this.form.errors.any() === false ) {
                    this.$notify({ type: 'is-success', text: this.$t('auth.webauthn.device_successfully_registered') })
                    this.$router.push({name: 'settings.webauthn.devices', params: { toRefresh: true }})
                }

            },

            cancelCreation: function() {

                this.$router.push({ name: 'settings.webauthn.devices' });
            },

        },

    }
</script>