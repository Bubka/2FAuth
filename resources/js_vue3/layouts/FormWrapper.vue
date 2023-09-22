<template>
    <responsive-width-wrapper>
        <h1 class="title has-text-grey-dark" v-html="title" v-if="title"></h1>
        <div id="punchline" v-if="punchline" class="block" v-html="punchline"></div>
        <slot />
    </responsive-width-wrapper>   
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