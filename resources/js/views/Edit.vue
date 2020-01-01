<template>
    <div class="modal modal-otp is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-background-black-ter ">
                            <form @submit.prevent="updateAccount">
                                <div class="field">
                                    <label class="label">Name</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Account name" v-model="twofaccount.name" required autofocus />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="email" placeholder="account email" v-model="twofaccount.email" required />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Icon</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="account icon" v-model="twofaccount.icon" required />
                                    </div>
                                </div>
                                <div class="field is-grouped">
                                    <div class="control">
                                        <router-link :to="{ name: 'accounts' }" class="button is-light">Cancel</router-link>
                                    </div>
                                    <div class="control">
                                        <button type="submit" class="button is-link">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
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

        created: function(){
            this.getAccount();
        },

        methods: {
            getAccount: function () {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('/api/twofaccounts/' + this.$route.params.twofaccountId).then(response => {
                    this.twofaccount = response.data
                })
            },

            updateAccount: function() {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.put('/api/twofaccounts/' + this.$route.params.twofaccountId, this.twofaccount).then(response => {
                    this.$router.push({name: 'accounts'});
                })
            },
        },

    }
</script>