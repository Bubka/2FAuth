<script setup>
    import { UseColorMode } from '@vueuse/components'
    import { useGroups } from '@/stores/groups'
    import { useBusStore } from '@/stores/bus'

    const router = useRouter()
    const groups = useGroups()
    const bus = useBusStore()

    const isFetching = ref(false)

    onMounted(async () => {
        // We want the store to be up-to-date to manage the groups
        isFetching.value = groups.isEmpty

        await groups.fetch()
        .finally(() => {
            isFetching.value = false
        })
    })

    // We use the beforeRouteLeave with the bus store to pass the group
    // name to the edit view to prevent a new request in the editGroup component.
    // This could be achieved using <a> anchor with a @click event here but then
    // the link won't have a href which is poor design for accessibility.
    onBeforeRouteLeave((to, from) => {
        if (to.name == 'editGroup') {
            bus.editedGroupName = groups.items.find(g => g.id == to.params.groupId)?.name
        }
    })

</script>

<template>
    <ResponsiveWidthWrapper>
        <h1 class="title has-text-grey-dark">
            {{ $t('groups.groups') }}
        </h1>
        <div class="is-size-7-mobile">
            {{ $t('groups.manage_groups_legend')}}
        </div>
        <div class="mt-3 mb-6">
            <RouterLink class="is-link mt-5" :to="{ name: 'createGroup' }">
                <FontAwesomeIcon :icon="['fas', 'plus-circle']" /> {{ $t('groups.create_group') }}
            </RouterLink>
        </div>
        <div v-if="!groups.isEmpty">
            <div v-for="group in groups.withoutTheAllGroup" :key="group.id" class="group-item is-size-5 is-size-6-mobile">
                {{ group.name }}
                <!-- delete icon -->
                <UseColorMode v-slot="{ mode }">
                    <button class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="groups.delete(group.id)"  :title="$t('commons.delete')">
                        {{ $t('commons.delete') }}
                    </button>
                </UseColorMode>
                <!-- edit link -->
                <RouterLink :to="{ name: 'editGroup', params: { groupId: group.id }}" class="has-text-grey px-1" :title="$t('commons.rename')">
                    <FontAwesomeIcon :icon="['fas', 'pen-square']" />
                </RouterLink>
                <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ group.twofaccounts_count }} {{ $t('twofaccounts.accounts') }}</span>
            </div>
            <div class="mt-2 is-size-7 is-pulled-right">
                {{ $t('groups.deleting_group_does_not_delete_accounts')}}
            </div>
        </div>
        <div v-if="isFetching && groups.isEmpty" class="has-text-centered">
            <span class="is-size-4">
                <FontAwesomeIcon :icon="['fas', 'spinner']" spin />
            </span>
        </div>
        <!-- footer -->
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: 'accounts' }" action="close" />
        </VueFooter>
    </ResponsiveWidthWrapper>
</template>