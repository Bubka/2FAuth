<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">{{ $t('auth.register') }}</h1>
                <form method="POST" action="/register">
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.name') }}</label>
                        <div class="control">
                            <input id="name" type="text" class="input" v-model="name" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.name">{{ validationErrors.name.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.email') }}</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.email">{{ validationErrors.email.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.password') }}</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="password" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.password">{{ validationErrors.password.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">{{ $t('auth.forms.confirm_password') }}</label>
                        <div class="control">
                            <input id="password_confirmation" type="password" class="input" v-model="password_confirmation" required />
                        </div>
                        <p class="help is-danger" v-if="validationErrors.passwordConfirmation">{{ validationErrors.passwordConfirmation.toString() }}</p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">{{ $t('auth.register') }}</button>
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
    export default {
        data(){
            return {
                name : '',
                email : '',
                password : '',
                password_confirmation : '',
                validationErrors: {},
                errorMessage: ''
            }
        },

        created: function() {
            // we check if a user account already exists
            axios.post('api/checkuser')
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

                axios.post('api/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation : this.password_confirmation
                })
                .then(response => {

                    localStorage.setItem('user',response.data.message.name)
                    localStorage.setItem('jwt',response.data.message.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.push({name: 'accounts'})
                    }
                })
                .catch(error => {

                    this.validationErrors = {}

                    if( error.response.data.validation ) {
                        this.validationErrors = error.response.data.validation
                    }
                    else {
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