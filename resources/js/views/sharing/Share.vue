<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import { useBusStore } from '@/stores/bus'
    import { asArray } from '@/composables/helpers'
    import { useFocus, onKeyStroke } from '@vueuse/core'
    import { LucideLoaderCircle, LucideSearch } from '@lucide/vue'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { UseColorMode } from '@vueuse/components'

    const $2fauth = inject('2fauth')
    const router = useRouter()
    const route = useRoute()
    const bus = useBusStore()
    const twofaccounts = useTwofaccounts()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accountSharing')

    const props = defineProps({
        twofaccountId: [Number, String]
    })

    onKeyStroke('Escape', (e) => {
        e.preventDefault()
        clearSearch()
    })

    const findUserForShareField = useTemplateRef('findUserForShare')
    const { focused: findUserForShareFocus } = useFocus(findUserForShareField)

    const twofaccount = ref(twofaccounts.items.find(account => account.id == props.twofaccountId))
    const isFetching = ref(false)
    const isSearching = ref(false)
    const isSending = ref(false)
    const searchQuery = ref('')
    const candidates = ref(null)
    const recipients = ref([])
    const shareWithAll = ref(false)
    
    const returnToObject = computed(() => {
        if (returnTo.value == 'accountSharing') {
            return { name: 'accountSharing', params: { twofaccountId: route.params.twofaccountId }}
        }
        else {
            return { name: returnTo.value }
        }
    })

    const showCandidates = computed(() => {
        return candidates.value?.length > 0
    })

    const hasRecipients = computed(() => {
        return recipients.value.length > 0
    })

    let timeout = null
    let recipientsSearchAbortController = null

    onMounted(() => {
        isFetching.value = true

        twofaccountService.get(props.twofaccountId).then(response => {
            twofaccount.value = response.data
        })
        .finally(() => {
            isFetching.value = false
        })
    })

    onBeforeMount(() => {
    })

    onBeforeUnmount(() => {
        clearSearch()
    })

    watch(searchQuery, (newValue) => {
        if (timeout) clearTimeout(timeout);

        if (newValue.length == 0) {
            clearCandidates()
        }
        else {
            timeout = setTimeout(() => {
                if (newValue.length > 1) {
                    searchRecipient(newValue)
                }
            }, 300)
        }
    })

    /**
     * Get list of recipients matching keyword
     */
    function searchRecipient(keyword) {
        recipientsSearchAbortController?.abort()
        recipientsSearchAbortController = new AbortController()

        const currentSearchAbortController = recipientsSearchAbortController
        const searchLoadingTimeout = setTimeout(() => {
            if (currentSearchAbortController === recipientsSearchAbortController) {
                isSearching.value = true
            }
        }, 250)

        shareService.getRecipients(props.twofaccountId, keyword, {
            signal: recipientsSearchAbortController.signal,
        }).then(response => {
            candidates.value = asArray(response.data)
        })
        .finally(() => {
            clearTimeout(searchLoadingTimeout)
            if (currentSearchAbortController === recipientsSearchAbortController) {
                isSearching.value = false
            }
        })
    }

    /**
     * Add a candidate to the recipients list & Clear search
     */
    function addToRecipients(candidate) {
        if (!recipients.value.some((recipient) => recipient.id == candidate.id)) {
            recipients.value.push(candidate)
        }
        clearCandidates()
        searchQuery.value = ''
        findUserForShareFocus.value = true
    }

    /**
     * Clear candidates list
     */
    function clearCandidates() {
        candidates.value = null
    }

    /**
     * Clear recipients list
     */
    function clearRecipients() {
        recipients.value = []
    }

    /**
     * Remove a recipient from the list
     */
    function removeRecipient(recipient) {
        for (var i=0 ; i < recipients.value.length ; i++) {
            if ( recipients.value[i].id === recipient.id ) {
                recipients.value.splice(i,1);
                return
            }
        }
    }

    /**
     * Clear search
     */
    function clearSearch() {
        recipientsSearchAbortController?.abort()
        recipientsSearchAbortController = null

        searchQuery.value = ''
        clearCandidates()
        isSearching.value = false
    }

    /**
     * Wrapper to call the appropriate function at form submit
     */
    function handleSubmit() {
        isSending.value = true

        if (recipients.value.length > 0) {
            shareService.shareWithUsers(props.twofaccountId, recipients.value.map(r => r.id)).then(response => {
                router.push({ name: 'accountSharing', params: { twofaccountId: props.twofaccountId }})
            })
            .finally(() => {
                isSending.value = false
            })
        }
    }

</script>

<template>
    <StackLayout>
        <template #content>
            <UseColorMode v-slot="{ mode }">
            <ResponsiveWidthWrapper>
                <h1 class="title mb-6">
                    {{ $t('heading.new_sharing') }}
                </h1>
                <div class="columns mb-5 is-mobile is-2 is-align-items-center">
                    <div class="column is-narrow">
                        <figure class="image is-32x32">
                            <img v-if="twofaccount.icon" role="presentation" :src="$2fauth.config.subdirectory + '/storage/icons/' + twofaccount.icon" alt="">
                            <img v-else-if="twofaccount.icon == null" role="presentation" :src="$2fauth.config.subdirectory + '/storage/noicon.svg'" alt="">
                        </figure>
                    </div>
                    <div class="column">
                        <p class="title is-5" :class="mode == 'dark' ? 'has-text-grey-lighter' : 'has-text-black'">{{ twofaccount.service }}</p>
                        <p class="subtitle is-7">{{ twofaccount.account }}</p>
                    </div>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="field">
                        <label for="findUserForShare" class="label" :class="{ 'is-opacity-5': shareWithAll }">
                            {{ $t('field.findUserForShare') }}
                        </label>
                        <div class="control" :class="{ 'has-icons-left' : leftIcon, 'has-icons-right': rightIcon }">
                            <div class="dropdown" :class="{ 'is-active': candidates || isSearching }">
                                <div class="dropdown-trigger">
                                    <div class="field">
                                        <p class="control has-icons-right">
                                            <input ref="findUserForShare" id="findUserForShare" v-model="searchQuery" type="text" class="input" autofocus :disabled="shareWithAll" />
                                            <span class="icon is-right">
                                                <button type="button" v-if="searchQuery != ''" id="btnClearSearch" tabindex="1" :title="$t('tooltip.clear_search')" class="clear-selection delete" @click="clearSearch"></button>
                                                <LucideSearch v-else />
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                                    <div class="dropdown-content is-shadowless">
                                        <template v-if="showCandidates">
                                            <template v-for="candidate in candidates" :key="candidate.id" >
                                                <a v-if="!candidate.is_shared_with" @click="addToRecipients(candidate)" class="dropdown-item">{{ candidate.name }}</a>
                                                <span v-else class="dropdown-item has-text-grey">
                                                    {{ candidate.name }} ({{ $t('message.already_shared') }})
                                                </span>
                                            </template>
                                        </template>
                                        <template v-else>
                                            <span v-if="isSearching" class="dropdown-item has-text-grey">
                                                <LucideLoaderCircle class="spinning" />
                                            </span>
                                            <span v-else class="dropdown-item is-italic">{{ $t('message.no_match') }}</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="help">{{ $t('field.findUserForShare.help') }}</p>
                    </div>
                    <div class="mb-5">
                        <div v-if="recipients.length > 0" class="field is-grouped is-grouped-multiline">
                            <div v-for="recipient in recipients" :key="recipient.id" class="control">
                                <div class="tags has-addons are-medium">
                                    <span class="tag" :class="mode == 'dark' ? 'is-dark' : 'is-white'">{{ recipient.name }}</span>
                                    <button @click="removeRecipient(recipient)" class="tag is-delete" :class="mode == 'dark' ? 'is-black' : 'is-dark'"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block is-size-7-mobile has-text-grey">
                        {{ $t('message.keep_in_mind_share_principle') }}
                    </div>
                    <FormButtons
                        :isDisabled="!hasRecipients && !shareWithAll"
                        :isBusy="isSending"
                        :submitLabel="'label.share'"
                        :showCancelButton="true"
                        @cancel="router.push(returnToObject)" />
                </form>
            </ResponsiveWidthWrapper>
            </UseColorMode>
        </template>
    </StackLayout>
</template>