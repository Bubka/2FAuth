<template>
    <div>
        <figure class="image is-64x64" :class="{ 'no-icon': !internal_icon }" style="display: inline-block">
            <img :src="'storage/icons/' + internal_icon" v-if="internal_icon">
        </figure>
        <p class="is-size-4 has-text-grey-light has-ellipsis">{{ internal_service }}</p>
        <p class="is-size-6 has-text-grey has-ellipsis">{{ internal_account }}</p>
        <p id="otp" class="is-size-1 has-text-white" :title="$t('commons.copy_to_clipboard')" v-clipboard="() => otp.replace(/ /g, '')" v-clipboard:success="clipboardSuccessHandler">{{ otp }}</p>
        <ul class="dots" v-if="type === 'totp'">
            <li v-for="n in 30"></li>
        </ul>
        <ul v-else-if="type === 'hotp'">
            <li>counter: {{ counter }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                id: null,
                internal_service: '',
                internal_account: '',
                internal_uri: '',
                next_uri: '',
                internal_icon: '',
                type: '',
                otp : '',
                timerID: null,
                position: null,
                counter: null,
            }
        },

        props: {
            service: '',
            account: '',
            uri : '',
            icon: ''
        },

        mounted: function() {
            this.showAccount()
        },

        methods: {

            async showAccount(id) {

                // 2 possible cases :
                //   - ID is provided so we fetch the account data from db but without the uri.
                //     This prevent the uri (a sensitive data) to transit via http request unnecessarily. In this
                //     case this.type is sent by the backend.
                //   - the URI prop has been set via the create form, we need to preview some OTP before storing the account.
                //     So this.type is set on client side from the provided URI
                
                this.id = id

                if( this.id || this.uri ) {
                    if( this.id ) {

                        const { data } = await this.axios.get('api/twofaccounts/' + this.id)

                        this.internal_service = data.service
                        this.internal_account = data.account
                        this.internal_icon = data.icon
                        this.type = data.type
                    }
                    else {

                        this.internal_service = this.service
                        this.internal_account = this.account
                        this.internal_icon = this.icon
                        this.internal_uri = this.uri
                        this.type = this.internal_uri.slice(0, 15 ) === "otpauth://totp/" ? 'totp' : 'hotp';
                    }

                    this.type === 'totp' ? await this.getTOTP() : await this.getHOTP()
                    this.$parent.isActive = true
                }
            },

            getTOTP: function() {

                this.axios.post('api/twofaccounts/otp', {data: this.id ? this.id : this.internal_uri }).then(response => {
                    let spacePosition = Math.ceil(response.data.otp.length / 2);
                    
                    this.otp = response.data.otp.substr(0, spacePosition) + " " + response.data.otp.substr(spacePosition);
                    this.position = response.data.position;

                    let dots = this.$el.querySelector('.dots');

                    // clear active dots
                    while (dots.querySelector('[data-is-active]')) {
                        dots.querySelector('[data-is-active]').removeAttribute('data-is-active');
                    }

                    // set dot at given position as the active one
                    let active = dots.querySelector('li:nth-child(' + (this.position + 1 ) + ')');
                    active.setAttribute('data-is-active', true);

                    let self = this;

                    this.timerID = setInterval(function() {

                        let sibling = active.nextSibling;

                            if(active.nextSibling === null) {
                                console.log('no more sibling to activate, we refresh the OTP')
                                self.stopLoop()
                                self.getTOTP();
                            }
                            else
                            {
                                active.removeAttribute('data-is-active');
                                sibling.setAttribute('data-is-active', true);
                                active = sibling
                            }

                    }, 1000);
                })
            },

            getHOTP: function() {

                this.axios.post('api/twofaccounts/otp', {data: this.id ? this.id : this.internal_uri }).then(response => {
                    let spacePosition = Math.ceil(response.data.otp.length / 2);
                    
                    this.otp = response.data.otp.substr(0, spacePosition) + " " + response.data.otp.substr(spacePosition)
                    this.counter = response.data.counter
                    this.next_uri = response.data.nextUri

                })
            },

            clearOTP: function() {
                this.stopLoop()
                this.id = this.timerID = this.position = this.counter = null
                this.internal_service = this.internal_account = this.internal_icon = this.internal_uri = ''
                this.otp = '... ...'

                try {
                    this.$el.querySelector('[data-is-active]').removeAttribute('data-is-active');
                    this.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
                }
                catch(e) {
                    // we do not throw anything
                }
            },

            stopLoop: function() {
                if( this.type === 'totp' ) {
                    clearInterval(this.timerID)
                }
            },

            clipboardSuccessHandler ({ value, event }) {
                console.log('success', value)
            },

            clipboardErrorHandler ({ value, event }) {
                console.log('error', value)
            }

        },

        beforeDestroy () {
            this.stopLoop()
        }
    }
</script>