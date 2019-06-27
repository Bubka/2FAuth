<template>
    <div>
        <div class="container">
            <div class="buttons are-large is-centered">
                <span v-for="account in accounts" class="button is-black twofaccount" @click.stop="getAccount(account.id)">
                    <img src="https://fakeimg.pl/64x64/">
                    {{ account.name }}
                    <span class="is-family-primary is-size-7 has-text-grey">{{ account.email }}</span>
                </span>
            </div>
        </div>
        <modal v-model="ModalIsActive">
            <modal-twofaccount
                :name='twofaccount.name'
                :icon='twofaccount.icon'
                :email='twofaccount.email'>
                <one-time-password :AccountId='twofaccount.id' ></one-time-password>
            </modal-twofaccount>
        </modal>
    </div>
</template>




<script>
    import Modal from '../components/Modal'
    import ModalTwofaccount from '../components/ModalTwofaccount'
    import OneTimePassword from '../components/OneTimePassword'

    // const ModalTwofaccount = {
    //     props: ['id', 'name', 'email', 'icon', 'totp'],
    //     template: `
    //         <section class="section">
    //             <div class="columns is-centered">
    //                 <div class="column is-three-quarters">
    //                     <div class="box has-text-centered has-background-black-ter ">
    //                         <figure class="image is-64x64" style="display: inline-block">
    //                             <img :src="icon">
    //                         </figure>
    //                         <p class="is-size-4 has-text-grey-light">{{ name }}</p>
    //                         <p class="is-size-6 has-text-grey">{{ email }}</p>
    //                         <p id="otp" title="refresh" class="is-size-1 has-text-white">{{ totp }}</p>
    //                         <ul class="dots">
    //                             <li data-is-active style="display:none"></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    //                         </ul>
    //                     </div>
    //                 </div>
    //             </div>
    //         </section>
    //     `
    // }



    export default {
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
                        icon : data.icon
                    })
                })
                // this.loadTasks()
            })
        },
        components: {
            Modal,
            ModalTwofaccount,
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
                    this.twofaccount.name = response.data.name
                    this.twofaccount.email = response.data.email
                    this.twofaccount.icon = response.data.icon

                    this.ModalIsActive = true;

                })
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

