<script setup>
    import SearchBox from '@/components/SearchBox.vue'
    import { useNotifyStore } from '@/stores/notify'
    import userService from '@/services/userService'
    import { FontAwesomeLayers } from '@fortawesome/vue-fontawesome'

    const notify = useNotifyStore()

    const props = defineProps({
        userId: [Number, String],
        lastOnly: Boolean,
        showSearch: Boolean
    })

    const authentications = ref([])
    const isFetching = ref(false)
    const searched = ref('')

    const visibleAuthentications = computed(() => {
        return authentications.value.filter(authentication => {
            return JSON.stringify(authentication)
            .toString()
            .toLowerCase()
            .includes(searched.value);
        })
    })

    onMounted(() => {
        getAuthentications()
    })

    /**
     * Gets user authentication logs
     */
    function getAuthentications() {
        isFetching.value = true
        let limit = props.lastOnly ? 3 : false

        userService.getauthentications(props.userId, limit, {returnError: true})
        .then(response => {
            authentications.value = response.data
        })
        .catch(error => {
            notify.error(error)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    const deviceIcon = (device) => {
        switch (device) {
            case "phone":
                return 'mobile-screen'
            case "tablet":
                return 'tablet-screen-button'
            default:
                return 'display'
        }
    }

    const isSuccessfulLogin = (authentication) => {
        return authentication.login_successful && authentication.login_at
    }

    const isSuccessfulLogout = (authentication) => {
        return !authentication.login_at && authentication.logout_at
    }

    const isFailedEntry = (authentication) => {
        return !authentication.login_successful && !authentication.logout_at
    }
</script>

<template>
    <SearchBox v-if="props.showSearch" v-model:keyword="searched" :hasNoBackground="true" />
    <div v-if="visibleAuthentications.length > 0">
        <div v-for="authentication in visibleAuthentications" :key="authentication.id" class="list-item is-size-6 is-size-7-mobile has-text-grey is-flex is-justify-content-space-between">
            <div>
                <div>
                    <span v-if="isFailedEntry(authentication)" v-html="$t('admin.failed_login_on', { login_at: authentication.login_at })" />
                    <span v-else-if="isSuccessfulLogout(authentication)" v-html="$t('admin.successful_logout_on', { login_at: authentication.logout_at })" />
                    <span v-else v-html="$t('admin.successful_login_on', { login_at: authentication.login_at })" />
                </div>
                <div>
                    {{ $t('commons.IP') }}: <span class="has-text-grey-light">{{ authentication.ip_address }}</span> -
                    {{ $t('commons.browser') }}: <span class="has-text-grey-light">{{ authentication.browser }}</span> -
                    {{ $t('commons.operating_system_short') }}: <span class="has-text-grey-light">{{ authentication.platform }}</span>
                </div>
            </div>
            <div class="is-align-self-center has-text-grey-darker">
                <font-awesome-layers class="fa-2x">
                    <FontAwesomeIcon :icon="['fas', deviceIcon(authentication.device)]" transform="grow-6" fixed-width />
                    <FontAwesomeIcon :icon="['fas', isFailedEntry(authentication) ? 'times' : 'check']"
                        :transform="'shrink-7' + (authentication.device == 'desktop' ? ' up-2' : '')"
                        :class="isFailedEntry(authentication) ? 'has-text-danger-dark' : 'has-text-success-dark'" fixed-width />
                </font-awesome-layers>
            </div>
        </div>
    </div>
    <div v-else-if="authentications.length == 0" class="mt-4">
        {{ $t('commons.no_entry_yet') }}
    </div>
    <div v-else class="mt-5 pl-3">
        {{ $t('commons.no_result') }}
    </div>
</template>