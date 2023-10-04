<script setup>
    import { RouterView } from 'vue-router'

    onMounted(async () => {
        const { useUserStore } = await import('./stores/user.js');
        const { language } = useNavigatorLanguage()

        watch(language, () => {
            useUserStore().applyLanguage()
        })
    })
</script>

<template>
    <notifications id="vueNotification" role="alert" width="100%" position="top" :duration="4000" :speed="0" :max="1" classes="notification is-radiusless" />
    <main class="main-section">
        <RouterView />
    </main>
</template>