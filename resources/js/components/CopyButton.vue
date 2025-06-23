<script setup>
    import { useNotify } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'
    const { t } = useI18n()

    const notify = useNotify()
    const { copy } = useClipboard({ legacy: true })

    const props = defineProps({
        token: String,
    })

    function copyToClipboard() {
        copy(props.token)
        notify.success({ text: t('message.copied_to_clipboard') })
    }
</script>

<template>
    <button type="button" :aria-label="$t('message.copy_to_clipboard')" :title="$t('message.copy_to_clipboard')" class="button is-like-text is-pulled-right is-small is-text" @click.stop="copyToClipboard()">
        <FontAwesomeIcon :icon="['fas', 'copy']" />
    </button>
</template>