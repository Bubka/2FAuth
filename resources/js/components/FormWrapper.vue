<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
            <h1 class="title" v-html="title" v-if="title"></h1>
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
        }
    }
</script>