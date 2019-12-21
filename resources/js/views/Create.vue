<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form @submit.prevent="createAccount">
                            <div class="field">
                                <label class="label">Name</label>
                                <div class="control">
                                    <input class="input" type="text" placeholder="Text input" v-model="twofaccount.name" required autofocus>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input class="input" type="email" placeholder="Text input" v-model="twofaccount.email" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Uri</label>
                                <div class="control">
                                    <input class="input" type="text" placeholder="Text input" v-model="twofaccount.uri" required>
                                </div>
                            </div>

                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-link">Submit</button>
                                </div>
                                <div class="control">
                                    <router-link :to="{ name: 'accounts' }" class="button is-text">Cancel</router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                twofaccount: {}
            }
        },

        methods: {

            createAccount: function() {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.post('/api/twofaccounts', this.twofaccount).then(response => {
                    this.$router.push({name: 'accounts'});
                })
            }
        },

    }
</script>