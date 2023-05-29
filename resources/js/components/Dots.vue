<template>
    <ul class="dots">
        <li v-for="n in stepCount" :key="n" :data-is-active="n == activeDot ? true : null"></li>
    </ul>
</template>

<script>
    export default {
        name: 'Dots',

        data() {
            return {
                activeDot: 0
            }
        },

        mounted() {
            if (this.initialIndex != null) {
                this.turnOn(this.initialIndex)
            }
        },

        props: {
            stepCount: {
                type: Number,
                default: 10
            },
            initialIndex: {
                type: Number,
                default: null
            },
        },

        methods: {

            /**
             * 
             */
            turnOn: function(index) {
                this.activeDot = index < 10 ? index + 1 : 1
            },
        },
    }
</script>