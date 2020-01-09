<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Register</h1>
                <form method="POST" action="/register">
                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input id="name" type="text" class="input" v-model="name" v-bind:class="{ 'is-danger' : errors.name }" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="errors.name">{{ errors.name.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" v-bind:class="{ 'is-danger' : errors.email }" required />
                        </div>
                        <p class="help is-danger" v-if="errors.email">{{ errors.email.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="password" v-bind:class="{ 'is-danger' : errors.password }" required />
                        </div>
                        <p class="help is-danger" v-if="errors.password">{{ errors.password.toString() }}</p>
                    </div>
                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <div class="control">
                            <input id="password_confirmation" type="password" class="input" v-model="password_confirmation" v-bind:class="{ 'is-danger' : errors.passwordConfirmation }" required />
                        </div>
                        <p class="help is-danger" v-if="errors.passwordConfirmation">{{ errors.passwordConfirmation.toString() }}</p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                Already registered? <router-link :to="{ name: 'login' }" class="is-link">
                    Sign in
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
                errors: {}
            }
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
                    localStorage.setItem('user',response.data.success.name)
                    localStorage.setItem('jwt',response.data.success.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.go('/');
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.error
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