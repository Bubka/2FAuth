<template>
    <form @submit.prevent="updateAccount">
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" type="text" placeholder="Text input" v-model="twofaccount.name" required autofocus />
            </div>
        </div>
        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input" type="email" placeholder="Text input" v-model="twofaccount.email" required />
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