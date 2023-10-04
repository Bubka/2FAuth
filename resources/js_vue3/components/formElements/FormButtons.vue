<script setup>
    const props = defineProps({
            showCancelButton: {
                type: Boolean,
                default: false
            },
            isBusy: {
                type: Boolean,
                default: false
            },
            isDisabled: {
                type: Boolean,
                default: false
            },
            caption: {
                type: String,
                default: 'commons.submit'
            },
            cancelLandingView: {
                type: String,
                default: ''
            },
            color: {
                type: String,
                default: 'is-link'
            },
            submitId: {
                type: String,
                default: 'btnSubmit'
            },
            cancelId: {
                type: String,
                default: 'btnCancel'
            },
    })
</script>

<template>
    <div class="field is-grouped">
        <div class="control">
            <VueButton :id="submitId" :color="color" :isLoading="isBusy" :disabled="isDisabled" >
                {{ $t(caption) }}
            </VueButton>
        </div>
        <div class="control" v-if="showCancelButton">
            <RouterLink :id="cancelId" :to="{ name: cancelLandingView }" class="button is-text">
                {{ $t('commons.cancel') }}
            </RouterLink>
        </div>
    </div>
</template>