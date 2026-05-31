<script setup>
    import { UseColorMode } from '@vueuse/components'

    const props = defineProps({
        twofaccount: {
            type: Object,
            required: true
        }
    })

    const $2fauth = inject('2fauth')

</script>

<template>
    <div class="columns is-mobile is-2 is-align-items-center">
    <UseColorMode v-slot="{ mode }">
        <div class="column is-narrow">
            <figure class="image is-32x32">
                <img v-if="twofaccount.icon" role="presentation" :src="$2fauth.config.subdirectory + '/storage/icons/' + twofaccount.icon" alt="">
                <img v-else-if="twofaccount.icon == null" role="presentation" :src="$2fauth.config.subdirectory + '/storage/noicon.svg'" alt="">
            </figure>
        </div>
        <div class="column">
            <p class="title is-5" :class="mode == 'dark' ? 'has-text-grey-lighter' : 'has-text-black'">
                {{ twofaccount.service }}
            </p>
            <p class="subtitle is-7">{{ twofaccount.account }}</p>
        </div>
    </UseColorMode>
    </div>
</template>