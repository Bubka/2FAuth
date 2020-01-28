<template>
    <form-wrapper :title="$t('auth.register')" :fail="fail" :success="success">
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
                <label class="label">{{ $t('auth.forms.password') }}</label>
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
            <div class="field">
                <div class="control">
                    <v-button :isLoading="form.isBusy" >{{ $t('auth.register') }}</v-button>
                </div>
            </div>
        </form>
        <p>{{ $t('auth.forms.already_register') }}&nbsp;<router-link :to="{ name: 'login' }" class="is-link">{{ $t('auth.sign_in') }}</router-link></p>
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
                    password_confirmation : '',
                })
            }
        },

        async created() {
            // we check if a user account already exists
            const { data } = await this.axios.post('api/checkuser')

            if( data.userCount > 0 ) {
                this.fail = this.$t('errors.already_one_user_registered') + ' ' + this.$t('errors.cannot_register_more_user')
                this.$router.push({ name: 'flooded' });
            }

        },

        methods : {
            async handleSubmit(e) {
                e.preventDefault()

                const { data } = await this.form.post('api/register')

                if( this.form.errors.any() === false ) {

                    localStorage.setItem('user',data.message.name)
                    localStorage.setItem('jwt',data.message.token)

                    if (localStorage.getItem('jwt') != null) {
                        this.$router.push({name: 'accounts'})
                    }
                }
            }
        },

        beforeRouteEnter (to, from, next) {
            if (localStorage.getItem('jwt')) {
                return next('/');
            }

            next();
        }
    }
</script>