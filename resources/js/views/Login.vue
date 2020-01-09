<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Login</h1>
                <form method="POST" action="/login">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" v-bind:class="{ 'is-danger' : errors.email }" required autofocus />
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
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="columns is-mobile is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                Don't have an account yet? <router-link :to="{ name: 'register' }" class="is-link">
                    Register
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                email : '',
                password : '',
                errors: {}
            }
        },
        methods : {
            handleSubmit(e){
                e.preventDefault()

                axios.post('api/login', {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    localStorage.setItem('user',response.data.success.name)
                    localStorage.setItem('jwt',response.data.success.token)

                    if (localStorage.getItem('jwt') != null){
                        this.$router.go('/');
                    }
                })
                .catch(error => {
                    if (error.response.status === 400) {
                        this.errors = error.response.data.error
                    }
                    else {
                        this.errors['password'] = [ 'Password do not match' ]
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