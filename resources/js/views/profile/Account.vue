<template>
    <form-wrapper :title="$t('auth.forms.edit_account')" :fail="fail" :success="success">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <div class="field">
                <label class="label">{{ $t('auth.forms.name') }}</label>
                <div class="control">
                    <input id="name" type="text" class="input" v-model="form.name" autofocus />
                </div>
                <field-error :form="form" field="name" />
            </div>
            <div class="field">
                <label class="label">{{ $t('auth.forms.email') }}</label>
                <div class="control">
                    <input id="email" type="email" class="input" v-model="form.email" />
                </div>
                <field-error :form="form" field="email" />
            </div>
            <div class="field">
                <label class="label">{{ $t('auth.forms.current_password') }}</label>
                <div class="control">
                    <input id="password" type="password" class="input" v-model="form.password" />
                </div>
                <field-error :form="form" field="password" />
            </div>
            <div class="field is-grouped">
                <div class="control">
                    <v-button :isLoading="form.isBusy" >{{ $t('commons.update') }}</v-button>
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
                    name : '',
                    email : '',
                    password : '',
                })
            }
        },

        async mounted() {
            const { data } = await this.form.get('/api/user')

            this.form.fill(data)
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.fail = ''
                this.success = ''

                this.form.patch('/api/user', {returnError: true})
                .then(response => {

                    this.success = response.data.message

                    localStorage.setItem('user',response.data.username)
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