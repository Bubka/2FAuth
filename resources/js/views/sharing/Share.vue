<script setup>
    import Form from '@/components/formElements/Form'
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import { useBusStore } from '@/stores/bus'
    import { asArray } from '@/composables/helpers'
    import { useFocus, onKeyStroke } from '@vueuse/core'
    import { LucideLoaderCircle, LucideSearch } from '@lucide/vue'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { UseColorMode } from '@vueuse/components'

    const router = useRouter()
    const route = useRoute()
    const bus = useBusStore()
    const twofaccounts = useTwofaccounts()

    const props = defineProps({
        twofaccountId: [Number, String]
    })

    onKeyStroke('Escape', (e) => {
        e.preventDefault()
        clearSearch()
    })

    const inputField = useTemplateRef('inputField')
    const { focused: inputFieldFocus } = useFocus(inputField)

    const twofaccount = ref(twofaccounts.items.find(account => account.id == props.twofaccountId))
    const isFetching = ref(false)
    const isSearching = ref(false)
    const isSending = ref(false)
    const searchQuery = ref('')
    const candidates = ref(null)
    const recipients = ref([])
    const shareWithAll = ref(false)
    
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
        inputFieldFocus.value = true
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
            <FormWrapper :title="'heading.new_sharing'">
                <div class="block">
                    <div class="is-left-bordered-link">
                        <p class="title is-5" :class="{ 'has-text-grey-lighter' : mode == 'dark' }">{{ twofaccount.service }}</p>
                        <p class="subtitle is-7 block">{{ twofaccount.account }}</p>
                    </div>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="field mt-6">
                        <label for="inputField" class="label" :class="{ 'is-opacity-5': shareWithAll }">
                            {{ $t('label.search_for_user') }}
                        </label>
                        <div class="control" :class="{ 'has-icons-left' : leftIcon, 'has-icons-right': rightIcon }">
                            <div class="dropdown" :class="{ 'is-active': candidates || isSearching }">
                                <div class="dropdown-trigger">
                                    <div class="field">
                                        <p class="control has-icons-right">
                                            <input ref="inputField" id="inputField" v-model="searchQuery" type="text" class="input" autofocus :disabled="shareWithAll" />
                                            <span class="icon is-right">
                                                <button type="button" v-if="searchQuery != ''" id="btnClearSearch" tabindex="1" :title="$t('tooltip.clear_search')" class="clear-selection delete" @click="clearSearch"></button>
                                                <LucideSearch v-else />
                                            </span>
                                        </p>
                                    </div>
                                    <!-- <input ref="inputField" v-model="searchQuery" type="text" class="input ghost" autofocus /> -->
                                </div>
                                <div class="dropdown-menu" id="dropdown-menu" role="menu">
                                    <div class="dropdown-content is-shadowless">
                                        <template v-if="showCandidates">
                                            <template v-for="candidate in candidates" :key="candidate.id" >
                                                <a v-if="!candidate.isSharedWith" @click="addToRecipients(candidate)" class="dropdown-item">{{ candidate.name }}</a>
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
                    <div class="block is-size-7-mobile">
                        {{ $t('message.keep_in_mind_share_principle') }}
                    </div>
                    <FormButtons
                        :isDisabled="!hasRecipients && !shareWithAll"
                        :isBusy="isSending"
                        :submitLabel="'label.share'"
                        :showCancelButton="true"
                        @cancel="router.push({ name: 'accountSharing', params: { twofaccountId: twofaccountId }})" />
                </form>
            </FormWrapper>
            </UseColorMode>
        </template>
    </StackLayout>
</template>