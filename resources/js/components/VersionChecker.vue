<template>
    <div class="columns is-mobile is-vcentered">
        <div class="column is-narrow">
            <button type="button" :class="isScanning ? 'is-loading' : ''" class="button is-link is-rounded is-small" @click="getLatestRelease">Check now</button>
        </div>
        <div class="column">
            <span v-if="$root.appSettings.latestRelease" class="mt-2 has-text-warning">
                <span class="release-flag"></span>{{ $root.appSettings.latestRelease }} is available <a class="is-size-7" href="https://github.com/Bubka/2FAuth/releases">View on Github</a>
            </span>
            <span v-if="isUpToDate" class="has-text-grey">
                {{ $t('commons.you_are_up_to_date') }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VersionChecker',

        data() {
            return {
                isScanning: false,
                isUpToDate: null,
            }
        },

        methods: {

            async getLatestRelease() {
                this.isScanning = true;

                await this.axios.get('/latestRelease').then(response => {
                    this.$root.appSettings['latestRelease'] = response.data.newRelease
                    this.isUpToDate = response.data.newRelease === false
                })

                this.isScanning = false;
            },
        }
    }
</script>
