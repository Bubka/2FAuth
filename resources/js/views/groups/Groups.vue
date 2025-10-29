<script setup>
    import { UseColorMode } from '@vueuse/components'
    import { useGroups } from '@/stores/groups'
    import { useBusStore } from '@/stores/bus'
    import { LucideCirclePlus, LucideSquarePen, LucideMenu } from 'lucide-vue-next'
    import { useSortable, moveArrayElement } from '@vueuse/integrations/useSortable'

    const router = useRouter()
    const groups = useGroups()
    const bus = useBusStore()

    const isFetching = ref(false)

    // Enables the sortable behaviour of the groups list
    const { start, stop } = useSortable('#dv', groups.withoutTheAllGroup, {
        animation: 200,
        handle: '.drag-handle',
        onUpdate: (e) => {
            const movedId = groups.withoutTheAllGroup[e.oldIndex].id
            const inItemsIndex = groups.items.findIndex(item => item.id == movedId)
            moveArrayElement(groups.items, inItemsIndex, e.newIndex + 1, e)

            nextTick(() => {
                groups.saveOrder()
            })
        }
    })

    watch(
        () => groups.isEmpty,
        (val) => {
            if (val == false) {
                nextTick(() => {
                    start()
                })
            }
            else stop()
        }
    )

    onMounted(() => {
        // We want the store to be up-to-date to manage the groups
        isFetching.value = groups.isEmpty

        groups.fetch()
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
            {{ $t('heading.groups') }}
        </h1>
        <div class="is-size-7-mobile">
            {{ $t('message.manage_groups_legend')}}
        </div>
        <div class="mt-3 mb-6">
            <RouterLink class="is-link mt-5" :to="{ name: 'createGroup' }">
                <LucideCirclePlus /> {{ $t('link.create_group') }}
            </RouterLink>
        </div>
        <div v-if="!groups.isEmpty">
            <UseColorMode v-slot="{ mode }">
                <span id="dv">
                    <div v-for="group in groups.withoutTheAllGroup" :key="group.id" class="group-item is-size-5 is-size-6-mobile">
                        {{ group.name }}
                        <LucideMenu class="drag-handle pt-1 ml-2 is-pulled-right has-text-grey is-draggable" />
                        <!-- delete icon -->
                        <button type="button" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark' : 'is-white'" @click="groups.remove(group.id)"  :title="$t('tooltip.delete')">
                            {{ $t('label.delete') }}
                        </button>
                        <!-- edit link -->
                        <RouterLink :to="{ name: 'editGroup', params: { groupId: group.id }}" class="has-text-grey px-1" :title="$t('tooltip.rename')">
                            <LucideSquarePen class="icon-size-1" />
                        </RouterLink>
                        <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ $t('message.x_accounts', { count: group.twofaccounts_count }) }}</span>
                    </div>
                </span>
            </UseColorMode>
            <div class="mt-2 is-size-7 is-pulled-right">
                {{ $t('message.deleting_group_does_not_delete_accounts')}}
            </div>
        </div>
        <Spinner :isVisible="isFetching && groups.isEmpty" type="list-loading" />
        <!-- footer -->
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: 'accounts' })" :current-page-title="$t('title.groups')" />
            </template>
        </VueFooter>
    </ResponsiveWidthWrapper>
</template>