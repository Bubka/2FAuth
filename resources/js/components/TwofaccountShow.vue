<template>
    <div>
        <figure class="image is-64x64" style="display: inline-block"  v-if="icon">
            <img :src="icon">
        </figure>
        <p class="is-size-4 has-text-grey-light">{{ service }}</p>
        <p class="is-size-6 has-text-grey">{{ account }}</p>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: ['twofaccountid', 'service', 'account', 'icon'],
    }
</script>