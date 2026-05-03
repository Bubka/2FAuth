<script setup>
    import Form from '@/components/formElements/Form'
    import twofaccountService from '@/services/twofaccountService'
    import shareService from '@/services/shareService'
    import { useNotify } from '@2fauth/ui'
    import { useBusStore } from '@/stores/bus'
    import { useErrorHandler } from '@2fauth/stores'
    import { asArray } from '@/composables/helpers'
    import { LucideLoaderCircle, LucideSearch } from '@lucide/vue'
    import { useTwofaccounts } from '@/stores/twofaccounts'
    import { UseColorMode } from '@vueuse/components'

    const { t } = useI18n()
    const errorHandler = useErrorHandler()
    const $2fauth = inject('2fauth')
    const router = useRouter()
    const route = useRoute()
    const bus = useBusStore()
    const notify = useNotify()
    const twofaccounts = useTwofaccounts()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accountSharing')
    const form = reactive(new Form({
        new_owner_id: '',
        confirm_password: ''
    }))

    const props = defineProps({
        twofaccountId: [Number, String]
    })

    const twofaccount = ref(twofaccounts.items.find(account => account.id == props.twofaccountId))
    const isFetchingRecipients = ref(false)
    const recipients = ref([])
    
    const returnToObject = computed(() => {
        if (returnTo.value == 'accountSharing') {
            return { name: 'accountSharing', params: { twofaccountId: route.params.twofaccountId }}
        }
        else {
            return { name: returnTo.value }
        }
    })

    const twofaccountShareStatus = computed(() => {
        if (twofaccount.value.is_shared) {
            return t('message.shared_with_specific_users')
        } else if (twofaccount.value.is_shared_with_all) {
            return t('message.shared_with_all')
        }
        else {
            return t('message.not_shared')
        }
    })

    onMounted(() => {
        refreshRecipients()
    })

    /**
     * Wrapper to call the appropriate function at form submit
     */
    function handleSubmit() {
        notify.clear()

        if (confirm(t('confirmation.you_will_loose_ownership'))) {
            form.patch(`/api/v1/twofaccounts/${props.twofaccountId}/owner`, {returnError: true}).then(response => {
                twofaccounts.items.splice(twofaccounts.items.findIndex(account => account.id == props.twofaccountId), 1)

                router.push({ name: 'accounts' })
            })
            .catch(error => {
                if( error.response?.status === 401 ) {
                    notify.alert({text: t('notification.authentication_failed'), duration: 10000 })
                }
                else if( error.response?.status === 422 ) {
                    form.clear()
                    form.errors.set(form.extractErrors(error.response))
                }
                else {
                    errorHandler.show(error)
                }
            })
        }
    }

    /**
     * Refreshes the list of available recipients
     */
    function refreshRecipients() {
        isFetchingRecipients.value = true

        shareService.getRecipients(props.twofaccountId, '').then(response => {
            asArray(response.data).forEach(recipient => {
                recipients.value.push({ text: recipient.name, value: recipient.id })
            });
        })
        .finally(() => {
            isFetchingRecipients.value = false
        })
    }

</script>

<template>
    <StackLayout>
        <template #content>
            <UseColorMode v-slot="{ mode }">
            <FormWrapper :title="'heading.transfer_ownership'">
                <div class="block is-size-7-mobile">
                    {{ $t('message.ownership_can_be_transferred_legend')}}
                </div>
                <div class="block">
                    <div class="is-left-bordered-link">
                        <p>
                            <span class="title is-5" :class="mode == 'dark' ? 'has-text-grey-lighter' : 'has-text-black'">{{ twofaccount.service }}</span>
                        </p>
                        <p class="subtitle is-7">{{ twofaccount.account }}</p>
                    </div>
                </div>
                <div class="block is-size-7-mobile">
                    <p class="mb-2">{{ $t('message.ownership_can_be_transferred_sharing_legend')}}</p>
                    <i18n-t keypath="message.this_account_is_currently_x" tag="p" :class="mode == 'dark' ? 'has-text-grey' : 'has-text-black'">
                        <template v-slot:sharing_status>
                            <RouterLink class="is-link" :to="{ name: 'accountSharing', params: { twofaccountId: props.twofaccountId } }">
                                {{ twofaccountShareStatus }}
                            </RouterLink>
                        </template>
                    </i18n-t>
                </div>
                <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)" class="mb-6">
                    <!-- user selector -->
                    <FormSelect v-model="form.new_owner_id" :options="recipients" fieldName="new_owner_id" :errorMessage="form.errors.get('new_owner_id')" is-loading="isFetchingRecipients" label="field.new_owner" help="field.new_owner.help" />
                    <div class="is-size-7-mobile mb-3" :class="mode == 'dark' ? 'has-text-grey' : 'has-text-black'">
                        {{ $t('message.double_check_new_owner') }}
                    </div>
                    <!-- current password -->
                    <FormPasswordField v-model="form.confirm_password" fieldName="confirm_password" :errorMessage="form.errors.get('confirm_password')" label="field.current_password" help="field.current_password.help" autocomplete="off" />
                    <FormButtons
                        :isDisabled="!form.new_owner_id || !form.confirm_password"
                        :isBusy="form.isBusy"
                        :submitLabel="'label.transfer'"
                        :showCancelButton="true"
                        @cancel="router.push(returnToObject)" />
                </form>
            </FormWrapper>
            </UseColorMode>
        </template>
    </StackLayout>
</template>