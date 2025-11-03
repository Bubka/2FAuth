<script setup>
    import twofaccountService from '@/services/twofaccountService'
    import groupService from '@/services/groupService'
    import { UseColorMode } from '@vueuse/components'

    const props = defineProps({
        showDestinationGroupSelector: Boolean,
        selectedAccountsIds: Array,
        groups: Array
    })
    const destinationGroupId = ref(null)

    const emit = defineEmits(['update:showDestinationGroupSelector', 'accounts-moved'])

    /**
     * Move accounts selected from the Edit mode to another group or withdraw them
     */
    async function moveAccounts() {
        // Backend will associate all provided accounts with the selected group in the same move
        // or withdraw the accounts if destination is 'no group' (id = 0)
        if (destinationGroupId.value === 0) {
            await twofaccountService.withdraw(props.selectedAccountsIds)
        }
        else await groupService.assign(props.selectedAccountsIds, destinationGroupId.value, { returnError: true })

        emit('accounts-moved')
    }

</script>

<template>
<!-- Group selector -->
    <div class="container group-selector">
        <div class="columns is-centered is-multiline">
            <div class="column is-full has-text-centered">
                {{ $t('message.move_selected_to') }}
            </div>
            <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                <div class="columns is-multiline">
                    <UseColorMode v-slot="{ mode }">
                        <div class="column is-full" v-for="group in groups" :key="group.id">
                            <button v-if="group.id > 0" type="button" class="button is-fullwidth" :class="{'is-link' : destinationGroupId === group.id, 'is-dark has-text-light is-outlined': mode == 'dark'}" @click="destinationGroupId = group.id">
                                {{ group.name }}
                            </button>
                        </div>
                        <div class="column has-text-centered">
                            {{ $t('message.or') }}
                        </div>
                        <div class="column is-full">
                            <button type="button" class="button is-fullwidth" :class="{'is-link' : destinationGroupId === 0, 'is-dark has-text-light is-outlined': mode == 'dark'}" @click="destinationGroupId = 0">
                                {{ $t('label.remove_from_group') }}
                            </button>
                        </div>
                    </UseColorMode>
                </div>
                <div class="columns is-centered">
                    <div class="column has-text-centered">
                        <RouterLink :to="{ name: 'groups' }" >{{ $t('link.manage_groups') }}</RouterLink>
                    </div>
                </div>
            </div>
        </div>
        <VueFooter>
            <template #default>
                <!-- Move to selected group button -->
                <p class="control">
                    <button type="button" class="button is-link is-rounded" @click="moveAccounts">{{ $t('label.apply') }}</button>
                </p>
                <NavigationButton action="cancel" :useLinkTag="false" @canceled="$emit('update:showDestinationGroupSelector', false)" />
            </template>
        </VueFooter>
    </div>
</template>