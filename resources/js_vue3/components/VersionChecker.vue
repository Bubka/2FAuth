<script setup>
    import systemService from '@/services/systemService'
    import { useAppSettingsStore } from '@/stores/appSettings'

    const appSettings = useAppSettingsStore()
    const isScanning = ref(false)
    const isUpToDate = ref(null)

    async function getLatestRelease() {
        isScanning.value = true;

        await systemService.getLastRelease()
        .then(response => {
            appSettings.latestRelease = response.data.newRelease
            isUpToDate.value = response.data.newRelease === false
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
                {{ $t('commons.you_are_up_to_date') }}
            </span>
        </div>
    </div>
</template>
