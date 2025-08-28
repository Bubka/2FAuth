<script setup>
    const { copy } = useClipboard({ legacy: true })
    import { useNotify } from '@2fauth/ui'
    import { LucideExternalLink } from 'lucide-vue-next'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const notify = useNotify()

    const props = defineProps({
        qrContent: String,
    })

    /**
     * Checks if a string is an url
     * 
     * @param {string} str 
     */
    function isUrl(str) {
        var strRegex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
        var re = new RegExp(strRegex)

        return re.test(str)
    }

    /**
     * Opens an uri in a new browser window
     * 
     * @param {string} uri 
     */
    function openInBrowser(uri) {
        const a = document.createElement('a')
        a.setAttribute('href', uri)
        a.dispatchEvent(new MouseEvent("click", { 'view': window, 'bubbles': true, 'cancelable': true }))
    }

    /**
     * Copies to clipboard and notify
     * 
     * @param {*} data 
     */
    function copyToClipboard(data) {
        copy(data)
        notify.success({ text: t('notification.copied_to_clipboard') })
    }
</script>

<template>
    <div class="too-bad"></div>
    <div class="block">
        {{ $t('error.data_of_qrcode_is_not_valid_URI') }}
    </div>
    <div class="block mb-6 light-or-darker">{{ qrContent ? qrContent : '[' + $t('message.nothing') + ']' }}</div>
    <!-- Copy to clipboard -->
    <div class="block has-text-link" v-if="qrContent">
        <button type="button" class="button is-link is-outlined is-rounded" @click.stop="copyToClipboard(qrContent)">
            {{ $t('label.copy_to_clipboard') }}
        </button>
    </div>
    <!-- Open in browser -->
    <div class="block has-text-link" v-if="isUrl(qrContent)" @click="openInBrowser(qrContent)">
        <button type="button" class="button is-link is-outlined is-rounded">
            <span>{{ $t('label.open_in_browser') }}</span>
            <span class="icon is-small">
                <LucideExternalLink />
            </span>
        </button>
    </div>
</template>
