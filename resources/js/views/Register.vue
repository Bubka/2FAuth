<template>
    <div class="section">
        <div class="columns is-mobile  is-centered">
            <div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
                <h1 class="title">Register</h1>
                <form method="POST" action="/register">
                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input id="name" type="email" class="input" v-model="name" v-bind:class="{ 'is-danger' : nameMissing }" required autofocus />
                        </div>
                        <p class="help is-danger" v-if="nameMissing">Field required</p>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input id="email" type="email" class="input" v-model="email" v-bind:class="{ 'is-danger' : emailMissing }" required />
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
                        <label class="label">Confirm Password</label>
                        <div class="control">
                            <input id="password-confirm" type="password" class="input" v-model="password_confirmation" v-bind:class="{ 'is-danger' : passwordConfirmationMissing }" required />
                        </div>
                        <p class="help is-danger" v-if="passwordConfirmationMissing">Field required</p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-link" @click="handleSubmit">Register</button>
                        </div>
                    </div>
                </form>
                <br />
                <span class="tag is-danger" v-if="errorMessage">
                    {{ errorMessage }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name : '',
                nameMissing : false,
                email : '',
                emailMissing : false,
                password : '',
                passwordMissing : false,
                password_confirmation : '',
                passwordConfirmationMissing : false,
                errorMessage : '',
            }
        },
        methods : {
            handleSubmit(e) {
                e.preventDefault()

                this.nameMissing = (this.name.length === 0) ? true : false;
                this.emailMissing = (this.email.length === 0) ? true : false;
                this.passwordMissing = (this.password.length === 0) ? true : false;
                this.passwordConfirmationMissing = (this.password_confirmation.length === 0) ? true : false;

                if( this.nameMissing || this.emailMissing || this.passwordMissing || this.passwordConfirmationMissing ) {
                    return false;
                }

                if (this.password === this.password_confirmation && this.password.length > 0)
                {
                    axios.post('api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        c_password : this.password_confirmation
                      })
                      .then(response => {
                        localStorage.setItem('user',response.data.success.name)
                        localStorage.setItem('jwt',response.data.success.token)

                        if (localStorage.getItem('jwt') != null){
                            this.$router.push({name: 'accounts'});
                        }
                      })
                      .catch(error => {
                        console.error(error);
                        this.errorMessage = error.message
                      });
                } else {
                    this.errorMessage = 'Passwords do not match'
                    return false;
                }
            }
        },
        beforeRouteEnter (to, from, next) {
            if (localStorage.getItem('jwt')) {
                return next('accounts');
            }

            next();
        }
    }
</script>