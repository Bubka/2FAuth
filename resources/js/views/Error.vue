<script setup>
    import { useErrorHandler } from '@2fauth/stores'
    import { UseColorMode } from '@vueuse/components'

    const errorHandler = useErrorHandler()
    const router = useRouter()
    const route = useRoute()

    const showModal = ref(true)
    const showDebug = computed(() => process.env.NODE_ENV === 'development')

    watch(showModal, (val) => {
        if (val == false) {
            exit()
        }
    })

    onMounted(() => {
        if (route.query.err) {
            errorHandler.message = 'error.' + route.query.err
        }
    })

    /**
     * Exits the error view
     */
    function exit() {
        window.history.length > 1 && route.name !== '404' && route.name !== 'notFound' && !route.query.err
            ? router.go(-1)
            : router.push({ name: 'accounts' })
    }

</script>

<template>
    <div>
        <Modal v-model="showModal">
            <UseColorMode v-slot="{ mode }">
                <div class="error-message" v-if="$route.name == '404' || $route.name == 'notFound'">
                    <p class="error-404"></p>
                    <p :class="{ 'has-text-grey' : mode != 'dark' }">{{ $t('error.resource_not_found') }}</p>
                </div>
                <div v-else class="error-message" >
                    <p class="error-generic"></p>
                    <p :class="{ 'has-text-grey' : mode != 'dark' }">{{ $t('message.error_occured') }} </p>
                    <p v-if="errorHandler.message" :class="{ 'has-text-grey-lighter' : mode == 'dark' }">{{ $t(errorHandler.message) }}</p>
                    <template v-if="errorHandler.reasons">
                        <p v-for="reason in errorHandler.reasons" :key="reason" :class="{ 'has-text-grey-lighter' : mode == 'dark' }">
                            {{ reason }}
                        </p>
                    </template>
                    <p v-if="errorHandler.originalMessage" :class="{ 'has-text-grey-lighter' : mode == 'dark' }">{{ $t(errorHandler.originalMessage) }}</p>
                    <p v-if="showDebug && errorHandler.debug" class="is-size-7 is-family-code pt-3"><br>{{ errorHandler.debug }}</p>
                </div>
            </UseColorMode>
        </Modal>
    </div>
</template>