<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import Spinner from '@/components/Spinner.vue'

    const router = useRouter()
    const route = useRoute()
    const qrcode = ref()

    onBeforeMount(() => {
        getQRcode()
    })

    /**
     * Get a QR code image resource from backend
     */
    async function getQRcode () {
        const { data } = await twofaccountService.getQrcode(route.params.twofaccountId)
        qrcode.value = data.qrcode
    }
</script>

<template>
    <div class="modal modal-otp is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <p class="has-text-centered m-5">
                <img v-if="qrcode" :src="qrcode" class="has-background-light" :alt="$t('commons.image_of_qrcode_to_scan')">
                <Spinner :isVisible="!qrcode" :type="'raw'" class="is-size-1" />
            </p>
        </div>
        <VueFooter :showButtons="true" :internalFooterType="'modal'">
            <ButtonBackCloseCancel :returnTo="{ name: 'accounts' }" action="close" />
        </VueFooter>
    </div>
</template>