<template>
    <div>
        <div class="container">
            <div class="has-text-centered">
                <a class="button is-dark" @click="editMode = true" v-if="!editMode">Manage</a>
                <a class="button is-dark" @click="editMode = false" v-if="editMode">Done</a>
            </div>
            <div class="buttons are-large is-centered">
                <span v-for="account in accounts" class="button is-black twofaccount" >
                    <span @click.stop="getAccount(account.id)">
                        <img :src="account.icon">
                        {{ account.service }}
                        <span class="is-family-primary is-size-7 has-text-grey">{{ account.account }}</span>
                    </span>
                    <span v-if="editMode">
                        <a v-on:click="deleteAccount(account.id)" class="is-size-6 has-text-grey-dark">
                            <font-awesome-icon :icon="['fas', 'trash']" />
                        </a> <router-link :to="{ name: 'edit', params: { twofaccountId: account.id }}" class="is-size-6 has-text-grey-dark">
                            <font-awesome-icon :icon="['fas', 'edit']" />
                        </router-link>
                    </span>
                </span>
            </div>
        </div>
        <modal v-model="ShowTwofaccountInModal">
            <twofaccount-show
                :twofaccountid='twofaccount.id'
                :service='twofaccount.service'
                :icon='twofaccount.icon'
                :account='twofaccount.account'>
                <one-time-password ref="OneTimePassword"></one-time-password>
            </twofaccount-show>
        </modal>
    </div>
</template>


<script>
    import Modal from '../components/Modal'
    import TwofaccountShow from '../components/TwofaccountShow'
    import OneTimePassword from '../components/OneTimePassword'

    export default {
        data(){
            return {
                accounts : [],
                ShowTwofaccountInModal : false,
                twofaccount: {},
                editMode : false,
            }
        },
        mounted(){
            let token = localStorage.getItem('jwt')

            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

            axios.get('api/twofaccounts').then(response => {
                response.data.forEach((data) => {
                    this.accounts.push({
                        id : data.id,
                        service : data.service,
                        account : data.account,
                        icon : data.icon
                    })
                })
            })

            this.$on('modalClose', function() {
                console.log('modalClose triggered')
                this.$refs.OneTimePassword.clearOTP()
            });

        },
        components: {
            Modal,
            TwofaccountShow,
            OneTimePassword
        },
        methods: {
            getAccount: function (id) {
                let token = localStorage.getItem('jwt')
                let accountId = id

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('api/twofaccounts/' + id).then(response => {

                    this.twofaccount.id = response.data.id
                    this.twofaccount.service = response.data.service
                    this.twofaccount.account = response.data.account
                    this.twofaccount.icon = response.data.icon

                    this.$refs.OneTimePassword.AccountId = response.data.id
                    this.$refs.OneTimePassword.getOTP()
                    this.ShowTwofaccountInModal = true;

                })
            },

            deleteAccount:  function (id) {
                if(confirm("Are you sure you want to delete this account?")) {
                    let token = localStorage.getItem('jwt')

                    axios.defaults.headers.common['Content-Type'] = 'application/json'
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                    axios.delete('/api/twofaccounts/' + id).then(response => {
                        this.accounts.splice(this.accounts.findIndex(x => x.id === id), 1);
                    })
                }
            }
        },
        beforeRouteEnter (to, from, next) {
            if ( ! localStorage.getItem('jwt')) {
                return next('login')
            }

            next()
        }
    }

</script>

