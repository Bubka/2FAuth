<script setup>
    const props = defineProps({
        title: {
            type: String,
            default: ''
        },
        punchline: {
            type: String,
            default: ''
        },
    })
</script>

<template>
    <ResponsiveWidthWrapper>
        <h1 class="title has-text-grey-dark" v-html="$t(title)" v-if="title"></h1>
        <div id="punchline" v-if="punchline" class="block" v-html="$t(punchline)" />
        <slot />
    </ResponsiveWidthWrapper>   
</template>