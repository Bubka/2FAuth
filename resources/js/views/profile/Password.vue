<template>
    <form-wrapper :title="$t('auth.forms.change_password')" :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <div class="field">
                <label class="label">{{ $t('auth.forms.current_password') }}</label>
                <div class="control">
                    <input id="currentPassword" type="password" class="input" v-model="form.currentPassword" />
                </div>
                <field-error :form="form" field="currentPassword" />
            </div>
            <div class="field">
                <label class="label">{{ $t('auth.forms.new_password') }}</label>
                <div class="control">
                    <input id="password" type="password" class="input" v-model="form.password" />
                </div>
                <field-error :form="form" field="password" />
            </div>
            <div class="field">
                <label class="label">{{ $t('auth.forms.confirm_password') }}</label>
                <div class="control">
                    <input id="password_confirmation" type="password" class="input" v-model="form.password_confirmation" />
                </div>
                <field-error :form="form" field="password_confirmation" />
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <v-button :isLoading="form.isBusy" >{{ $t('auth.forms.change_password') }}</v-button>
                </div>
                <div class="control">
                    <router-link :to="{ name: 'profile' }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
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
                    currentPassword : '',
                    password : '',
                    password_confirmation : '',
                })
            }
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.fail = ''
                this.success = ''

                this.form.patch('/api/password', {returnError: true})
                .then(response => {

                    this.success = response.data.message
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