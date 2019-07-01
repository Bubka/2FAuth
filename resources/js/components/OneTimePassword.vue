<template>
    <div>
        <p id="otp" title="refresh" class="is-size-1 has-text-white">{{ totp }}</p>
        <ul class="dots">
            <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                totp : '',
                componentKey: 0,
                timerID: null,
                position: null,
                AccountId : null
            }
        },
        //props: ['AccountId'],
        // watch: {
        //     AccountId: function(newVal, oldVal) {
        //         this.getOTP()
        //     }
        // },
        methods: {
            getOTP: function () {

                // if( !this.$props.AccountId ) {
                //     console.log("AccountId is undefined")
                //     return
                // }

                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('api/twofaccounts/' + this.AccountId + '/totp').then(response => {
                    this.totp = response.data.totp.substr(0, 3) + " " + response.data.totp.substr(3);
                    this.position = response.data.position;

                    let dots = this.$el.querySelector('.dots');
                    //let lastdot = dots.querySelector('li:last-child');

                    // clear active dots
                    while (dots.querySelector('[data-is-active]')) {
                        dots.querySelector('[data-is-active]').removeAttribute('data-is-active');
                    }

                    // set dot at given position as the active one
                    let active = dots.querySelector('li:nth-child(' + (this.position + 1 ) + ')');
                    active.setAttribute('data-is-active', true);

                    // if (active === null) {
                    //     this.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
                    //     active = dots.querySelector('[data-is-active]');
                    // }

                    let self = this;

                    this.timerID = setInterval(function() {

                        let sibling = active.nextSibling;

                        // axios.get('api/twofaccounts/' + self.AccountId + '/totp').then(response => {
                            // console.log(response.data.totp.substr(0, 3) + " " + response.data.totp.substr(3))
                            // console.log(response.data.position);

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
                        // })


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
            }
        },
        beforeDestroy () {
            this.stopLoop()
        }
    }
</script>