<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
            <h1 class="title" v-html="title" v-if="title"></h1>
            <slot />
            <p v-if="showTag">
                <notification :message="fail" type="is-danger" v-if="fail" />
                <notification :message="success" type="is-success" v-if="success" />
            </p>
        </div>
    </div>    
</template>

<script>
    export default {
        name: 'FormWrapper',
        
        data() {
            return {

            }
        },

        computed: {
            showTag: function() {
                return (this.fail || this.success) ? true : false
            }
        },

        props: {
            title: {
                type: String,
                default: ''
            },

            fail: {
                type: String,
                default: ''
            },

            success: {
                type: String,
                default: ''
            },
        }
    }
</script>