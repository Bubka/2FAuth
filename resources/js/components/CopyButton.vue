<script setup>
    import { useNotifyStore } from '@/stores/notify'

    const notify = useNotifyStore()
    const { copy } = useClipboard({ legacy: true })

    const props = defineProps({
        token: String,
    })

    function copyToClipboard() {
        copy(props.token)
        notify.success({ text: trans('commons.copied_to_clipboard') })
    }
</script>

<template>
    <button :aria-label="$t('commons.copy_to_clipboard')" class="button is-like-text is-pulled-right is-small is-text" @click.stop="copyToClipboard()">
        <FontAwesomeIcon :icon="['fas', 'copy']" />
    </button>
</template>