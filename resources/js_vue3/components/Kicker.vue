<script setup>
    import { useUserStore } from '@/stores/user'

    const user = useUserStore()
    const events = ref(['click', 'mousedown', 'scroll', 'keypress', 'load'])
    const logoutTimer = ref(null)
    // const elapsed = ref(0)
    // const counter = ref(null)

    const props = defineProps({
        kickAfter: {
            type: Number,
            required: true
        },
    })

    onMounted(() => {
        events.value.forEach(function (event) {
            window.addEventListener(event, resetTimer)
        }, this)

        setTimer()
    })

    onUnmounted(() => {
        events.value.forEach(function (event) {
            window.removeEventListener(event, resetTimer)
        }, this)

        clearTimeout(logoutTimer.value)
        // clearInterval(counter.value)
    })

    function setTimer() {
        // elapsed.value = 0
        // clearInterval(counter.value)

        logoutTimer.value = setTimeout(logoutUser, props.kickAfter * 60 * 1000)
        // counter.value = setInterval(() => {
        //     elapsed.value += 1
        //     console.log(elapsed.value + '/' + props.kickAfter * 60)
        // }, 1000);
    }

    // Triggers the user logout
    function logoutUser() {
        clearTimeout(logoutTimer.value)
        console.log('inativity detected, user kicked out')

        user.logout({ kicked: true})
    }

    function resetTimer() {
        clearTimeout(logoutTimer.value)
        setTimer()
    }
</script>

<template>

</template>