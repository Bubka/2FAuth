<template>
    <div>
        <p id="otp" title="refresh" class="is-size-1 has-text-white">{{ totp }}</p>
        <ul class="dots">
            <li data-is-active style="display:none"></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                totp : '',
                componentKey: 0
            }
        },
        props: ['AccountId'],
        watch: {
            AccountId: function(newVal, oldVal) {
                this.getOTP()
            }
        },
        methods: {
            getOTP: function () {
                let token = localStorage.getItem('jwt')

                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token

                axios.get('api/twofaccounts/' + this.$props.AccountId + '/totp').then(response => {
                    this.totp = response.data.totp.substr(0, 3) + " " + response.data.totp.substr(3);
                })

                var self = this;
                var timer = setInterval(function() {
                    let dots = self.$el.querySelector('.dots');
                    let active = dots.querySelector('[data-is-active]');
                    let lastdot = dots.querySelector('li:last-child');

                    if (active === null) {
                        self.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
                        active = dots.querySelector('[data-is-active]');
                    }
                    else if(active === lastdot) {
                        clearInterval(timer);
                        active.removeAttribute('data-is-active');
                        self.$el.querySelector('.dots li:first-child').setAttribute('data-is-active', true);
                        self.getOTP();
                    }
                    else
                    {
                        let sibling = active.nextSibling;
                        if (sibling === null) return;

                        active.removeAttribute('data-is-active');
                        sibling.setAttribute('data-is-active', true);
                    }
                },3000);

            }
        }
    }
</script>