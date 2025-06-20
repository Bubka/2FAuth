<script setup>
    import systemService from '@/services/systemService'
    import { useAppSettingsStore } from '@/stores/appSettings'
    import { UseColorMode } from '@vueuse/components'

    const appSettings = useAppSettingsStore()
    const isScanning = ref(false)
    const isUpToDate = ref()

    async function getLatestRelease() {
        isScanning.value = true;
        isUpToDate.value = undefined

        await systemService.getLastRelease({returnError: true})
        .then(response => {
            appSettings.latestRelease = response.data.newRelease
            isUpToDate.value = response.data.newRelease === null ? null : response.data.newRelease === false
        })
        .catch(() => {
            isUpToDate.value = null
        })

        isScanning.value = false;
    }

</script>

<template>
    <div class="columns is-mobile is-vcentered">
        <div class="column is-narrow">
            <button type="button" :class="isScanning ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="getLatestRelease">{{ $t('message.admin.check_now') }}</button>
        </div>
        <div class="column">
            <UseColorMode v-slot="{ mode }">
                <span v-if="appSettings.latestRelease" class="mt-2" :class="mode == 'dark' ? 'has-text-warning' : 'has-text-warning-dark'">
                    <span class="release-flag"></span>{{ $t('message.admin.x_is_available', { version: appSettings.latestRelease }) }}&nbsp;<a class="is-size-7" href="https://github.com/Bubka/2FAuth/releases">{{ $t('message.admin.view_on_github') }}</a>
                </span>
                <span v-if="isUpToDate" class="has-text-grey">
                    <FontAwesomeIcon :icon="['fas', 'check']" class="mr-1 has-text-success" /> {{ $t('message.you_are_up_to_date') }}
                </span>
                <span v-else-if="isUpToDate === null" class="has-text-grey">
                    <FontAwesomeIcon :icon="['fas', 'times']" class="mr-1 has-text-danger" />{{ $t('error.check_failed_try_later') }}
                </span>
            </UseColorMode>
        </div>
    </div>
</template>
