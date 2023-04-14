<template>
    <ul class="dots">
        <li v-for="n in 10" :key="n"></li>
    </ul>
</template>

<script>
    export default {
        name: 'Dots',

        data() {
            return {
            }
        },

        methods: {
            /**
             * 
             */
            turnOn: function(index) {
                const dots = this.$el.querySelectorAll('[data-is-active]')
                dots.forEach((dot) => {
                    dot.removeAttribute('data-is-active')
                });

                if (index < 10) {
                    const dot = this.$el.querySelector('.dots li:nth-child(' + (index + 1) + ')')
                    if (dot) {
                        dot.setAttribute('data-is-active', true)
                    }
                }
                else {
                    this.$el.querySelector('.dots li:nth-child(1)').setAttribute('data-is-active', true)
                }
            },
        },
    }
</script>