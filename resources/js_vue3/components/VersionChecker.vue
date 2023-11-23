<script setup>
    import systemService from '@/services/systemService'
    import { useAppSettingsStore } from '@/stores/appSettings'

    const appSettings = useAppSettingsStore()
    const isScanning = ref(false)
    const isUpToDate = ref()

    async function getLatestRelease() {
        isScanning.value = true;
        isUpToDate.value = undefined

        await systemService.getLastRelease({returnError: true})
        .then(response => {
            appSettings.latestRelease = response.data.newRelease
            isUpToDate.value = response.data.newRelease === false
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
            <button type="button" :class="isScanning ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="getLatestRelease">Check now</button>
        </div>
        <div class="column">
            <span v-if="appSettings.latestRelease" class="mt-2 has-text-warning">
                <span class="release-flag"></span>{{ appSettings.latestRelease }} is available <a class="is-size-7" href="https://github.com/Bubka/2FAuth/releases">View on Github</a>
            </span>
            <span v-if="isUpToDate" class="has-text-grey">
                <FontAwesomeIcon :icon="['fas', 'check']" class="mr-1 has-text-success" /> {{ $t('commons.you_are_up_to_date') }}
            </span>
            <span v-else-if="isUpToDate === null" class="has-text-grey">
                <FontAwesomeIcon :icon="['fas', 'times']" class="mr-1 has-text-danger" />{{ $t('errors.check_failed_try_later') }}
            </span>
        </div>
    </div>
</template>
