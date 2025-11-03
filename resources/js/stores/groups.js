import { defineStore } from 'pinia'
import { useUserStore } from '@/stores/user'
import { useNotify } from '@2fauth/ui'
import groupService from '@/services/groupService'

export const useGroups = defineStore('groups', () => {

    const user = useUserStore()
    const notify = useNotify()

    // STATE

    const items = ref([])
    const fetchedOn = ref(null)

    // GETTERS

    const current = computed(() => {
        const group = items.value.find(item => item.id === parseInt(user.preferences.activeGroup))

        return group && user.preferences.activeGroup != 0 ? group.name : null
    })

    const orderedIds = computed(() => {
        return items.value.map(a => a.id)
    })

    const withoutTheAllGroup = computed(() => items.value.filter(item => item.id > 0))
    const theAllGroup = computed(() => items.value.find(item => item.id == 0))
    const isEmpty = computed(() => withoutTheAllGroup.value.length == 0)
    const count = computed(() => withoutTheAllGroup.value.length)

    // ACTIONS

    function $reset() {
        items.value = [];
        fetchedOn.value = null;
    }

    /**
     * Adds or edits a group
     * @param {object} group 
     */
    function addOrEdit(group) {
        const index = items.value.findIndex(g => g.id === parseInt(group.id))
        
        if (index > -1) {
            items.value[index] = group
            notify.success({ text: this.$i18n.global.t('notification.group_name_saved') })
        }
        else {
            items.value.push(group)
            notify.success({ text: this.$i18n.global.t('notification.group_successfully_created') })
        }            
    }

    /**
     * Fetches the groups collection from the backend
     */
    async function fetch() {
        // We do not want to fetch fresh data multiple times in the same 2s timespan
        const age = Math.floor(Date.now() - fetchedOn.value)
        const isNotFresh = age > 2000

        if (isNotFresh) {
            fetchedOn.value = Date.now()

            await groupService.getAll().then(response => {
                items.value = response.data
            })
        }
    }
    
    /**
     * Deletes a group
     */
    async function remove(id) {
        if (confirm(this.$i18n.global.t('confirmation.delete_group'))) {
            await groupService.delete(id).then(response => {
                items.value = items.value.filter(a => a.id !== id)
                notify.success({ text: this.$i18n.global.t('notification.group_successfully_deleted') })

                // Reset group filter to 'All' (groupId=0) since the backend has already made
                // the change automatically. This prevents a new request.
                if( parseInt(user.preferences.activeGroup) === id ) {
                    user.preferences.activeGroup = 0
                }
            })
        }
    }
    
    /**
     * Saves the groups order to db
     */
    function saveOrder() {
        groupService.saveOrder(orderedIds.value)
    }

    return {
        // STATE
        items,
        fetchedOn,

        // GETTERS
        current,
        orderedIds,
        withoutTheAllGroup,
        theAllGroup,
        isEmpty,
        count,

        // ACTIONS
        $reset,
        addOrEdit,
        fetch,
        remove,
        saveOrder,
    }
})
