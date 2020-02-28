<template>
    <div class="notification" :class="type" v-if="show">
        <button class="delete" v-if="isDeletable" @click="close"></button>
        {{ message }}
    </div>
</template>

<script>
    export default {
        name: 'Notification',
        
        data() {
            return {
                show: true
            }
        },

        props: {
            type: {
                type: String,
                default: 'is-primary'
            },

            message: {
                type: String,
                default: '',
            },

            isDeletable: {
                type: Boolean,
                default: true,
            },
        },

        methods: {

            close (event) {
                if (event) {
                    this.show = false
                }
                
            }
        }
    }
</script>