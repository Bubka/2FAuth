<script setup>
    const props = defineProps({
        provider: {
            type: String,
            default: 'unknown'
        }
    })

    const icons = {
        unknown: {
            collection: 'fa',
            icon: 'globe',
        },
        github: {
            collection: 'fab',
            icon: 'github-alt',
        },
        openid: {
            collection: 'fab',
            icon: 'openid',
        }
    }
</script>

<template>
    <a :id="'lnkSignWith' + props.provider" class="button is-link" :href="'socialite/redirect/' + props.provider">
        {{ $t('label.' + props.provider) }}
    </a>
    
</template>

