<script setup>
    import { RouterView } from 'vue-router'
    const route = useRoute()
    const kickUserAfter = ref(null)
    const isProtectedRoute = ref(null)

    watch(
        () => route.name,
        () => {
            isProtectedRoute.value = protectedRoute(route)
        }
    )

    const kickInactiveUser = computed(() => kickUserAfter.value > 0 && isProtectedRoute.value)

    onBeforeMount(async () => {
        const { useUserStore } = await import('./stores/user.js')
        const { language } = useNavigatorLanguage()
        const user = useUserStore()

        kickUserAfter.value = parseInt(user.preferences.kickUserAfter)
        isProtectedRoute.value = protectedRoute(route)

        watch(
            () => user.preferences.kickUserAfter,
            () => {
                kickUserAfter.value = parseInt(user.preferences.kickUserAfter)
            }
        )

        watch(language, () => {
            user.applyLanguage()
        })
    })

    function protectedRoute(route) {
        let bool = false
        route.meta.middlewares?.forEach(func => {
            if (func instanceof Function && func.name == 'auth') {
                bool = true
                return
            }
        })
        return bool
    }

</script>

<template>
    <notifications id="vueNotification" role="alert" width="100%" position="top" :duration="4000" :speed="0" :max="1" classes="notification is-radiusless" />
    <main class="main-section">
        <RouterView />
    </main>
    <kicker v-if="kickInactiveUser" :kickAfter="kickUserAfter"></kicker>
</template>