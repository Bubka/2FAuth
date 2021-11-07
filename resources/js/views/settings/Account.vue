<template>
    <div>
        <setting-tabs :activeTab="'settings.account'"></setting-tabs>
        <div class="options-tabs">
            <form-wrapper>
                <form @submit.prevent="submitProfile" @keydown="formProfile.onKeydown($event)">
                    <h4 class="title is-4 has-text-grey-light">{{ $t('settings.profile') }}</h4>
                    <form-field :form="formProfile" fieldName="name" :label="$t('auth.forms.name')" autofocus />
                    <form-field :form="formProfile" fieldName="email" inputType="email" :label="$t('auth.forms.email')" />
                    <form-field :form="formProfile" fieldName="password" inputType="password" :label="$t('auth.forms.current_password.label')" :help="$t('auth.forms.current_password.help')" />
                    <form-buttons :isBusy="formProfile.isBusy" :caption="$t('commons.update')" />
                </form>
                <form @submit.prevent="submitPassword" @keydown="formPassword.onKeydown($event)">
                    <h4 class="title is-4 pt-6 has-text-grey-light">{{ $t('settings.change_password') }}</h4>
                    <form-field :form="formPassword" fieldName="password" inputType="password" :label="$t('auth.forms.new_password')" />
                    <form-field :form="formPassword" fieldName="password_confirmation" inputType="password" :label="$t('auth.forms.confirm_new_password')" />
                    <form-field :form="formPassword" fieldName="currentPassword" inputType="password" :label="$t('auth.forms.current_password.label')" :help="$t('auth.forms.current_password.help')" />
                    <form-buttons :isBusy="formPassword.isBusy" :caption="$t('auth.forms.change_password')" />
                </form>
            </form-wrapper>
        </div>
        <vue-footer :showButtons="true">
            <!-- Cancel button -->
            <p class="control">
                <a class="button is-dark is-rounded" @click.stop="exitSettings">
                    {{ $t('commons.close') }}
                </a>
            </p>
        </vue-footer>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                formProfile: new Form({
                    name : '',
                    email : '',
                    password : '',
                }),
                formPassword: new Form({
                    currentPassword : '',
                    password : '',
                    password_confirmation : '',
                })
            }
        },

        async mounted() {
            const { data } = await this.formProfile.get('/api/v1/user')

            this.formProfile.fill(data)
        },

        methods : {
            submitProfile(e) {
                e.preventDefault()

                this.formProfile.put('/api/v1/user', {returnError: true})
                .then(response => {
                    this.$notify({ type: 'is-success', text: this.$t('auth.forms.profile_saved') })
                })
                .catch(error => {
                    if( error.response.status === 400 ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.message })
                    }
                    else if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            },

            submitPassword(e) {
                e.preventDefault()

                this.formPassword.patch('/api/v1/user/password', {returnError: true})
                .then(response => {

                    this.$notify({ type: 'is-success', text: response.data.message })
                })
                .catch(error => {
                    if( error.response.status === 400 ) {

                        this.$notify({ type: 'is-danger', text: error.response.data.message })
                    }
                    else if( error.response.status !== 422 ) {
                        
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
            }
        },
    }
</script>