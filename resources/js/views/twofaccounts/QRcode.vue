<template>
    <div class="modal modal-otp is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <p class="has-text-centered m-5">
                <img :src="qrcode" class="has-background-light">
            </p>
        </div>
        <div class="fullscreen-footer">
            <!-- Close button -->
            <label class="button is-dark is-rounded" @click.stop="$router.push({name: 'accounts', params: { InitialEditMode: true }});">
                {{ $t('commons.close') }}
            </label>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                qrcode: null,
            }
        },

        mounted: function() {

            this.getQRcode()
        },

        methods: {

            /**
             * Get a QR code image resource from backend
             */
            async getQRcode () {

                const { data } = await this.axios.get('/api/twofaccounts/' + this.$route.params.twofaccountId + '/qrcode')
                this.qrcode = data.qrcode
                
            },


        }
    }

</script>