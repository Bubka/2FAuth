<script setup>
    import { RouterView } from 'vue-router'
    import { Kicker } from '@2fauth/ui'

    const { t } = useI18n()
    const { language } = useNavigatorLanguage()
    const route = useRoute()
    const user = inject('userStore')

    const mustKick = ref(false)
    const kickUserAfter = ref(null)
    const isProtectedRoute = ref(route.meta.watchedByKicker)

    mustKick.value = user.isAuthenticated
    kickUserAfter.value = parseInt(user.preferences.kickUserAfter)

    watch(
        () => user.preferences.kickUserAfter,
        () => {
            kickUserAfter.value = parseInt(user.preferences.kickUserAfter)
        }
    )
    watch(
        () => user.isAuthenticated,
        () => {
            mustKick.value = user.isAuthenticated
        }
    )

    watch(language, () => {
        user.applyLanguage()
    })

    watch(
        () => route.name,
        () => {
            isProtectedRoute.value = route.meta.watchedByKicker
        }
    )

    router.afterEach((to, from) => {
        to.meta.title = t('title.' + to.name)
        document.title = to.meta.title
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
    <Kicker
        v-if="mustKick && kickUserAfter > 0 && isProtectedRoute"
        :kickAfter="kickUserAfter"
        @kicked="() => user.logout({ kicked: true})"
    />
</template>