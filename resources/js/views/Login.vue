<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Login</h1>
                <form method="POST" action="/login">
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" v-bind:class="{ 'is-danger' : emailMissing }" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="emailMissing">Field required</p>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input id="password" type="password" class="input" v-model="password" v-bind:class="{ 'is-danger' : passwordMissing }" required />
                        </div>
                        <p class="help is-danger" v-if="passwordMissing">Field required</p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">Sign in</button>
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
                emailMissing : false,
                password : '',
                passwordMissing : false,
                errorMessage : '',
            }
        },
        methods : {
            handleSubmit(e){
                e.preventDefault()

                this.emailMissing = (this.email.length === 0) ? true : false;
                this.passwordMissing = (this.password.length === 0) ? true : false;

                if (this.password.length > 0 && this.email.length > 0) {

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
                      .catch(e => {
                        console.error(e);

                        if (e.response.status === 401) {
                            this.errorMessage = 'bad credential, please try again'
                        }
                        else {
                            this.errorMessage = 'An error occured, please retry'
                        }
                      });
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