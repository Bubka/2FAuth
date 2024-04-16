<script setup>
    import AccessLogViewer from '@/components/AccessLogViewer.vue'
    import userService from '@/services/userService'
    import { useNotifyStore } from '@/stores/notify'
    import { useBusStore } from '@/stores/bus'

    const bus = useBusStore()
    const router = useRouter()

    onMounted(async () => {
        getUser()
    })

    const props = defineProps({
        userId: [Number, String]
    })

    const username = ref(bus.username ?? '')

    /**
     * Gets the user from backend
     */
    function getUser() {
        userService.getById(props.userId, {returnError: true})
        .then(response => {
            username.value = response.data.info.name
        })
    }
    
</script>

<template>
    <ResponsiveWidthWrapper>
        <h1 class="title has-text-grey-dark">
            {{ $t('titles.admin.logs.access') }}
        </h1>
        <div class="block is-size-7-mobile">
            {{ $t('admin.access_log_legend_for_user', { username: username }) }} (#{{ props.userId }})
        </div>
        <AccessLogViewer :userId="props.userId" :lastOnly="false" :showSearch="true" :period="1" />
        <!-- footer -->
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: 'admin.manageUser', params: { userId: props.userId }}" action="back" />
            <ButtonBackCloseCancel :returnTo="{ name: 'accounts' }" action="close" />
        </VueFooter>
    </ResponsiveWidthWrapper>
</template>
