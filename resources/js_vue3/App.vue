<script setup>
    import { RouterView } from 'vue-router'
    const route = useRoute()
    const kickUser = ref(null)
    const kickUserAfter = ref(null)
    const isProtectedRoute = ref(route.meta.watchedByKicker)

    watch(
        () => route.name,
        () => {
            isProtectedRoute.value = route.meta.watchedByKicker
        }
    )

    // const kickInactiveUser = computed(() => kickUser && kickUserAfter.value > 0 && isProtectedRoute.value)

    onBeforeMount(async () => {
        const { useUserStore } = await import('./stores/user.js')
        const { language } = useNavigatorLanguage()
        const user = useUserStore()

        kickUserAfter.value = parseInt(user.preferences.kickUserAfter)
        kickUser.value = user.isAuthenticated

        watch(
            () => user.preferences.kickUserAfter,
            () => {
                kickUserAfter.value = parseInt(user.preferences.kickUserAfter)
            }
        )
        watch(
            () => user.isAuthenticated,
            () => {
                kickUser.value = user.isAuthenticated
            }
        )
        watch(language, () => {
            user.applyLanguage()
        })
    })

</script>

<template>
    <notifications
        id="vueNotification"
        role="alert"
        width="100%"
        position="top"
        :duration="4000"
        :speed="0"
        :max="1"
        classes="notification notification-banner is-radiusless" />
    <main class="main-section">
        <RouterView />
    </main>
    <kicker v-if="kickUser && kickUserAfter > 0 && isProtectedRoute" :kickAfter="kickUserAfter"></kicker>
</template>