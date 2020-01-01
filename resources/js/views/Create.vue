<template>
    <div class="modal modal-otp is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-background-black-ter ">
                            <form @submit.prevent="createAccount">
                                <div class="field">
                                    <label class="label">Name</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Name" v-model="twofaccount.name" required autofocus />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Email</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Email" v-model="twofaccount.email" required />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Uri</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Uri" v-model="twofaccount.uri" required />
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Icon</label>
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Icon" v-model="twofaccount.icon" required />
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