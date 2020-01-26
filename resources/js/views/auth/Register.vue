<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.register') }}</h1>
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
                <br />
                <span class="tag is-danger" v-if="errorMessage">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                {{ $t('auth.forms.already_register') }}&nbsp;<router-link :to="{ name: 'login' }" class="is-link">
                    {{ $t('auth.sign_in') }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data(){
            return {
                errorMessage: '',
                form: new Form({
                    name : '',
                    email : '',
                    password : '',
                    password_confirmation : '',
                })
            }
        },

        created: function() {
            // we check if a user account already exists
            this.axios.post('api/checkuser')
            .then(response => {
                if( response.data.userCount > 0) {
                    this.errorMessage = this.$t('errors.already_one_user_registered') + ' ' + this.$t('errors.cannot_register_more_user')
                    this.$router.push({ name: 'flooded' });
                }
            })
            .catch(error => {
                this.$router.push({ name: 'genericError', params: { err: error.response } });
            });
        },

        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.form.post('api/register')
                .then(response => {
                    localStorage.setItem('user',response.data.message.name)
                    localStorage.setItem('jwt',response.data.message.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.push({name: 'accounts'})
                    }
                })
                .catch(error => {
                    if( error.response.status !== 422 ) {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    }
                });
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