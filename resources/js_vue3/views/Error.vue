<script setup>
    import { useNotifyStore } from '@/stores/notify'
    
    const errorHandler = useNotifyStore()
    const router = useRouter()
    const route = useRoute()

    const showModal = true
    const showDebug = computed(() => process.env.NODE_ENV === 'development')

    const props = defineProps({
        closable: {
            type: Boolean,
            default: true
        }
    })

    function exit() {
        window.history.length > 1 && route.name !== '404' ? router.go(-1) : router.push({ name: 'accounts' })
    }

</script>

<template>
    <div class="error-message">
        <modal v-model="showModal" :closable="props.closable">
            <div class="error-message" v-if="$route.name == '404' || $route.name == 'notFound'">
                <p class="error-404"></p>
                <p>{{ $t('errors.resource_not_found') }}</p>
            </div>
            <div v-else>
                <p class="error-generic"></p>
                <p>{{ $t('errors.error_occured') }} </p>
                <p v-if="errorHandler.message" class="has-text-grey-lighter">{{ errorHandler.message }}</p>
                <p v-if="errorHandler.originalMessage" class="has-text-grey-lighter">{{ errorHandler.originalMessage }}</p>
                <p v-if="showDebug && errorHandler.debug" class="is-size-7 is-family-code"><br>{{ errorHandler.debug }}
                </p>
            </div>
        </modal>
    </div>
</template>