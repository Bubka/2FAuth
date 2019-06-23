<template>
    <div>
        <div class="container">
            <div class="buttons are-large is-centered">
                <span v-for="account in accounts" class="button is-black twofaccount" @click.stop="getOTP(account.id)">
                    <img src="https://fakeimg.pl/64x64/">
                    {{ account.name }}
                    <span class="is-family-primary is-size-7 has-text-grey">{{ account.email }}</span>
                </span>
            </div>
        </div>
        <modal v-model="ModalIsActive">
            <modal-twofaccount v-bind="twofaccount"></modal-twofaccount>
        </modal>
    </div>
</template>




<script>
    import Modal from '../components/Modal'

    const ModalTwofaccount = {
        props: ['id', 'name', 'email', 'uri', 'icon', 'totp'],
        template: `
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-text-centered has-background-black-ter ">
                            <figure class="image is-64x64" style="display: inline-block">
                                <img :src="icon">
                            </figure>
                            <p class="is-size-4 has-text-grey-light">{{ name }}</p>
                            <p class="is-size-6 has-text-grey">{{ email }}</p>
                            <p id="otp" title="refresh" class="is-size-1 has-text-white">{{ totp }}</p>
                            <ul class="dots">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li data-is-active></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        `
    }

    export default {
        name: 'Accounts',
        data(){
            return {
                accounts : [],
                ModalIsActive : false,
                twofaccount: {}
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
                        name : data.name,
                        email : data.email,
                        uri : data.uri,
                        icon : data.icon
                    })
                })
                // this.loadTasks()
            })
        },
        components: {
            Modal,
            ModalTwofaccount
        },
        methods: {
            getOTP: function (id) {
                let token = localStorage.getItem('jwt')
                let twofa

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('api/twofaccounts/' + id).then(response => {
                    let res1 = response.data
                    axios.get('api/twofaccounts/' + id + '/totp').then(response => {
                        this.twofaccount = Object.assign(res1, response.data)
                        this.twofaccount.totp = this.twofaccount.totp.substr(0, 3) + " " + this.twofaccount.totp.substr(3);
                    })
                })


                console.log(this.twofaccount)
                this.ModalIsActive = true
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

