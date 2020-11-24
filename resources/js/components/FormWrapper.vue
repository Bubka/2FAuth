<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
            <h1 class="title" v-html="title" v-if="title"></h1>
            <div v-if="punchline" class="block" v-html="punchline"></div>
            <slot />
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

        props: {
            title: {
                type: String,
                default: ''
            },

            punchline: {
                type: String,
                default: ''
            },
        }
    }
</script>