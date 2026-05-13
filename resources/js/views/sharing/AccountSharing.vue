<script setup>
    import { useNotify, SearchBox } from '@2fauth/ui'
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import { asArray } from '@/composables/helpers'
    import { useI18n } from 'vue-i18n'
    import { UseColorMode } from '@vueuse/components'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { useUserStore } from '@/stores/user'
    import { LucideUsers, LucideCirclePlus } from '@lucide/vue'

    const props = defineProps({
        twofaccountId: [Number, String]
    })

    const notify = useNotify()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const twofaccounts = useTwofaccounts()
    const twofaccount = ref(twofaccounts.items.find(account => account.id == props.twofaccountId))
    const user = useUserStore()

    const activeScopeRef = useTemplateRef('activeScopeRef')

    const isFetching = ref(false)
    const usershares = ref([])
    const userFilter = ref('')
    const shareButtonRefs = ref([])
    const sharingScopes = [
        { id: 'ShareWithAll', label: 'label.share_with_all' },
        { id: 'NotShared', label: 'label.not_shared' },
        { id: 'ShareWithUsers', label: 'label.share_with_specific_users' }
    ]
    const activeSharingScope = ref('NotShared')

    const visibleUsershares = computed(() => {
        return usershares.value.filter(
            user => {
                return user.name.toLowerCase().includes(userFilter.value.toLowerCase())
            }
        )
    })

    const activeScopeLabel = computed(() => {
        return sharingScopes.find((scope) => scope.id == activeSharingScope.value).label
    })

    const isSharedWithAll = computed(() => {
        return activeSharingScope.value == 'ShareWithAll'
    })

    const isNotShared = computed(() => {
        return activeSharingScope.value == 'NotShared'
    })

    const isSharedWithUsers = computed(() => {
        return activeSharingScope.value == 'ShareWithUsers'
    })

    onMounted(() => {
        isFetching.value = true
        activeSharingScope.value = 'NotShared'

        twofaccountService.get(props.twofaccountId).then(response => {
            twofaccount.value = response.data
        })

        shareService.getShares(props.twofaccountId).then(response => {
            if (response?.data?.is_shared_with_all) {
                activeSharingScope.value = 'ShareWithAll'
            }
            else {
                usershares.value = asArray(response?.data?.specific_users)
                if (usershares.value.length > 0) {
                    activeSharingScope.value = 'ShareWithUsers'
                }
                else activeSharingScope.value = 'NotShared'
            }
        })
        .finally(() => {
            isFetching.value = false
            toggleSharingButton(activeSharingScope.value)
        })
    })

    /**
     * 
     * @param userId
     */
    function unshareUser(userId) {
        if (confirm(t('confirmation.are_you_sure')) === true) {
            shareService.unshareWithUser(props.twofaccountId, userId).then(response => {
                usershares.value = usershares.value.filter(user => user.id != userId)
            })
        }
    }

    /**
     * 
     * @param newScope 
     */
    function confirmToggleSharing(newScope) {
        if ((activeSharingScope.value == "NotShared" && newScope == "ShareWithUsers")
            || (newScope == "NotShared" && activeSharingScope.value == "ShareWithUsers" && usershares.value.length == 0)) {
            toggleSharing(newScope)
        }
        else if (confirm(t('confirmation.toggle_sharing_setting')) === true) {
            toggleSharing(newScope)
        }
    }

    /**
     * 
     * @param newScope 
     */
    function toggleSharing(newScope) {
        isFetching.value = true
        let sharePromise

        switch (newScope) {
            case "ShareWithAll":
                sharePromise = shareService.shareWithAll(props.twofaccountId)
                break

            case "NotShared":
                sharePromise = shareService.unshare(props.twofaccountId)
                break

            case "ShareWithUsers":
                if (activeSharingScope.value == "ShareWithAll") {
                    sharePromise = shareService.unshare(props.twofaccountId)
                }
                else sharePromise = Promise.resolve(true)
                break

            default:
                notify.alert({ text: t('error.unsupported_sharing_scope') })
                return
        }

        sharePromise.then(response => {
            activeSharingScope.value = newScope

            if (newScope != "ShareWithUsers") {
                usershares.value = []
            }
            
            toggleSharingButton(newScope)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    /**
     * 
     * @param newScope 
     */
    function toggleSharingButton(newScope) {
        const btnRef = shareButtonRefs.value.find((btn) => btn.id == 'btn' + newScope)

        activeScopeRef.value.style.setProperty('transform', `translateX(${btnRef.offsetLeft}px)`)
        activeScopeRef.value.textContent = btnRef.textContent
    }

</script>

<template>
    <StackLayout>
        <template #content>
            <UseColorMode v-slot="{ mode }">
            <ResponsiveWidthWrapper>
                <h1 class="title mb-6">
                    {{ $t('heading.sharing_setting') }}
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
                <div class="columns is-mobile is-multiline">
                    <div class="column is-narrow">
                    <div class="toggle-wrapper has-background-dark p-1">
                        <div class="toggle">
                            <div class="toggle-buttons">
                                <button 
                                    v-for="sharingScope in sharingScopes" 
                                    :key="sharingScope.id"
                                    ref="shareButtonRefs"
                                    :id="'btn' + sharingScope.id"
                                    @click="confirmToggleSharing(sharingScope.id)"
                                    class="button px-3 is-dark">
                                    {{ $t(sharingScope.label) }}
                                </button>
                            </div>
                            <label ref="activeScopeRef" :class="{ 'is-black' : activeSharingScope == 'NotShared', 'is-link' : activeSharingScope != 'NotShared', 'is-loading': isFetching}" class="toggle-active button px-3">{{ $t(activeScopeLabel) }}</label>
                        </div>
                    </div>
                    </div>
                </div>
                 <template v-if="isFetching == false">
                    <template v-if="isSharedWithUsers">
                        <div class="pt-2 mb-4">
                            <RouterLink class="is-link" :to="{ name: 'shareAccount', params: { twofaccountId: props.twofaccountId } }">
                                <LucideCirclePlus class="mr-2" /> {{ $t('link.add_users') }}
                            </RouterLink>
                        </div>
                        <div v-if="usershares.length == 0" class="list-item is-size-6 is-size-7-mobile has-text-grey pt-2">
                            {{ $t('message.add_your_first_user_to_share_this_account_with') }}
                        </div>
                        <template v-if="usershares.length > 0">
                            <SearchBox v-if="usershares.length > 5" v-model:keyword="userFilter" :hasNoBackground="true" :isSmall="true" />
                            <div v-for="user in visibleUsershares" :key="user.id" class="list-item is-size-5 is-size-6-mobile">
                                {{ user.name }}
                                <!-- revoke icon -->
                                <button type="button" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="unshareUser(user.id)"  :title="$t('tooltip.revoke')">
                                    {{ $t('label.revoke') }}
                                </button>
                                <span class="is-block is-family-primary is-size-7 has-text-grey">{{ $t('message.shared_since_x', { date: user.is_shared_since }) }}</span>
                            </div>
                        </template>
                    </template>
                    <template v-else-if="isSharedWithAll == true">
                        <div class="list-item is-size-5 is-size-6-mobile">
                            <span class="icon-text">
                                <span class="icon">
                                    <LucideUsers class="icon-size-1" />
                                </span>
                                <span>{{ $t('message.is_shared_with_all_users') }}</span>
                            </span>
                            <p class="is-size-6 is-size-7-mobile has-text-grey pt-2">{{ $t('message.is_shared_with_all_users_legend') }}</p>
                        </div>
                    </template>
                    <div v-else class="list-item pt-0">
                        <p class="is-size-6 is-size-7-mobile has-text-grey pt-2">{{ $t('message.all_users_meaning') }}</p>
                        <p class="is-size-6 is-size-7-mobile has-text-grey pt-2">{{ $t('message.selected_users_meaning') }}</p>
                    </div>
                    <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('message.you_can_toggle_sharing_setting_at_any_time')}}
                    </div>
                 </template>
            </ResponsiveWidthWrapper>
            </UseColorMode>
        </template>
        <template #footer>
            <VueFooter>
                <template #default>
                    <NavigationButton action="close" @closed="router.push({ name: 'accounts' })" :current-page-title="$t('title.accountSharing')" />
                </template>
            </VueFooter>
        </template>
    </StackLayout>
</template>