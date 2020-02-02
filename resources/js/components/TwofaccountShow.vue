<template>
    <div>
        <figure class="image is-64x64" style="display: inline-block"  v-if="icon">
            <img :src="'storage/icons/' + icon">
        </figure>
        <p class="is-size-4 has-text-grey-light has-ellipsis">{{ service }}</p>
        <p class="is-size-6 has-text-grey has-ellipsis">{{ account }}</p>
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
                service: '',
                account: '',
                icon: '',
                type : '',
                otp : '',
                timerID: null,
                position: null,
                counter: null,
            }
        },

        methods: {

            async getAccount(id) {

                this.id = id

                const { data } = await this.axios.get('api/twofaccounts/' + this.id)

                this.service = data.service
                this.account = data.account
                this.icon = data.icon
                this.type = data.type

                this.type === 'totp' ? await this.getTOTP() : await this.getHOTP()
                this.$parent.isActive = true
                
            },

            getTOTP: function() {

                this.axios.get('api/twofaccounts/' + this.id + '/otp').then(response => {
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

                this.axios.get('api/twofaccounts/' + this.id + '/otp').then(response => {
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