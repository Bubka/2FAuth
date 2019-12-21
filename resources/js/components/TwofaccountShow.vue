<template>
    <div>
        <router-link :to="{ name: 'edit', params: { twofaccountId: twofaccountid }}">Edit</router-link>
        <router-link :to="{ name: 'delete', params: { twofaccountId: twofaccountid }}">Delete</router-link>
        <figure class="image is-64x64" style="display: inline-block">
            <img :src="icon">
        </figure>
        <p class="is-size-4 has-text-grey-light">{{ name }}</p>
        <p class="is-size-6 has-text-grey">{{ email }}</p>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: ['twofaccountid', 'name', 'email', 'icon'],
    }
</script>