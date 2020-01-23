<template>
    <div>
        <figure class="image is-64x64" style="display: inline-block"  v-if="icon">
            <img :src="'storage/icons/' + icon">
        </figure>
        <p class="is-size-4 has-text-grey-light">{{ service }}</p>
        <p class="is-size-6 has-text-grey">{{ account }}</p>
        <p id="otp" class="is-size-1 has-text-white" :title="$t('commons.copy_to_clipboard')" v-clipboard="() => totp.replace(/ /g, '')" v-clipboard:success="clipboardSuccessHandler">{{ totp }}</p>
        <ul class="dots">
            <li v-for="n in 30"></li>
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
                totp : '',
                timerID: null,
                position: null,
            }
        },

        methods: {

            getAccount: function(id) {

                this.id = id

                axios.get('api/twofaccounts/' + this.id)
                    .then(response => {

                        this.service = response.data.service
                        this.account = response.data.account
                        this.icon = response.data.icon

                        this.getOTP()
                    })
                    .catch(error => {
                        this.$router.push({ name: 'genericError', params: { err: error.response } });
                    });
            },

            getOTP: function() {

                axios.get('api/twofaccounts/' + this.id + '/totp').then(response => {
                    let spacePosition = Math.ceil(response.data.totp.length / 2);
                    
                    this.totp = response.data.totp.substr(0, spacePosition) + " " + response.data.totp.substr(spacePosition);
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
                                console.log('no more sibling to activate, we refresh the TOTP')
                                self.stopLoop()
                                self.getOTP();
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

            clearOTP: function() {
                this.stopLoop()
                this.timerID = null
                this.totp = '... ...'
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