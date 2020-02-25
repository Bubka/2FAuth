<template>
    <div class="field is-grouped">
        <div class="control">
            <v-button :isLoading="isBusy" :disabled="isDisabled" >{{ caption }}</v-button>
        </div>
        <div class="control" v-if="showCancelButton">
            <router-link :to="{ name: cancelLandingView }" class="button is-text">{{ $t('commons.cancel') }}</router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FormButtons',
        
        data() {
            return {

            }
        },

        props: {
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
                default: 'Submit'
            },

            cancelLandingView: {
                type: String,
                default: ''
            },
        }
    }
</script>