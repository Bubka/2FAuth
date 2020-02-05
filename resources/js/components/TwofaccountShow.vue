<template>
    <div>
        <figure class="image is-64x64" style="display: inline-block">
            <img :src="'storage/icons/' + internal_icon" v-if="internal_icon">
        </figure>
        <p class="is-size-4 has-text-grey-light has-ellipsis">{{ internal_service }}</p>
        <p class="is-size-6 has-text-grey has-ellipsis">{{ internal_account }}</p>
        <p id="otp" class="is-size-1 has-text-white" :title="$t('commons.copy_to_clipboard')" v-clipboard="() => otp.replace(/ /g, '')" v-clipboard:success="clipboardSuccessHandler">{{ otp }}</p>
        <ul class="dots" v-if="internal_type === 'totp'">
            <li v-for="n in 30"></li>
        </ul>
        <ul v-else-if="internal_type === 'hotp'">
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
                internal_icon: '',
                internal_uri: '',
                internal_type: '',
                otp : '',
                timerID: null,
                position: null,
                counter: null,
            }
        },

        computed: {
            setService: {
                get: function () { return this.internal_service },
                set: function(value) { this.internal_service = value }
            },
            setAccount: {
                get: function () { return this.internal_account },
                set: function(value) { this.internal_account = value }
            },
            setIcon: {
                get: function () { return this.internal_icon },
                set: function(value) { this.internal_icon = value }
            },
            setUri: {
                get: function () { return this.internal_uri },
                set: function(value) { this.internal_uri = value }
            },
            setType: {
                get: function () { return this.internal_type },
                set: function(value) { this.internal_type = value }
            },
        },

        props: {
            service: '',
            account: '',
            uri : '',
            type: '',
        },

        mounted: function() {
            if( this.uri && this.type ) {

                this.setService = this.service
                this.setAccount = this.account
                this.setUri = this.uri
                this.setType = this.type

                this.internal_type === 'totp' ? this.getTOTP() : this.getHOTP()
            }
        },

        methods: {

            async getAccount(id) {

                this.id = id

                const { data } = await this.axios.get('api/twofaccounts/' + this.id)

                this.setService = data.service
                this.setAccount = data.account
                this.setIcon = data.icon
                this.setType = data.type

                this.internal_type === 'totp' ? await this.getTOTP() : await this.getHOTP()
                this.$parent.isActive = true
                
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

                this.axios.get('api/twofaccounts/otp', {data: this.id ? this.id : this.internal_uri }).then(response => {
                    let spacePosition = Math.ceil(response.data.otp.length / 2);
                    
                    this.otp = response.data.otp.substr(0, spacePosition) + " " + response.data.otp.substr(spacePosition);
                    this.counter = response.data.counter;
                })
            },

            clearOTP: function() {
                this.stopLoop()
                this.id = this.timerID = this.position = this.counter = null
                this.service = this.account = this.icon = this.type = ''
                this.otp = '... ...'
                this.$el.querySelector('[data-is-active]').removeAttribute('data-is-active');
                this.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
            },

            stopLoop: function() {
                clearInterval(this.timerID)
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