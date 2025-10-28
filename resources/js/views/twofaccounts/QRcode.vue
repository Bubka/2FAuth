<script setup>
    import twofaccountService from '@/services/twofaccountService'

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
        <div class="modal-content modal-with-footer">
            <p class="has-text-centered m-5">
                <img v-if="qrcode" :src="qrcode" class="qrcode has-background-light" :alt="$t('alttext.image_of_qrcode_to_scan')">
                <Spinner :isVisible="!qrcode" :type="'raw'" :rawSize="32" />
            </p>
        </div>
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: 'accounts' })" :current-page-title="$t('title.showQRcode')" />
            </template>
            <template #subpart>
                <!-- we add a subpart to hide the default slot value -->
                <router-link v-if="$route.name != 'accounts'" id="lnkBackToHome" :to="{ name: 'accounts' }" class="has-text-grey">{{ $t('link.back_to_home') }}</router-link>
                <span v-else>&nbsp;</span>
            </template>
        </VueFooter>
    </div>
</template>