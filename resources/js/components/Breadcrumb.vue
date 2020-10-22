<template>
    <div class="mb-5">
        <router-link :to="{ name: 'accounts' }">
            <font-awesome-icon :icon="['fas', 'chevron-left']" />
            Back
        </router-link>
    </div>
</template>

<script>
    export default {
        name: 'Breadcrumb',

        props: {
            routeName: {
                type: String,
                default: '/'
            },

            caption: {
                type: String,
                default: ''
            },
        }
    }

</script>