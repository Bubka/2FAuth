import { defineStore } from 'pinia'
import { useUserStore } from '@/stores/user'
import { useNotifyStore } from '@/stores/notify'
import groupService from '@/services/groupService'

export const useGroups = defineStore({
    id: 'groups',

    state: () => {
        return {
            items: [],
        }
    },

    getters: {
        current(state) {
            const group = state.items.find(item => item.id === parseInt(useUserStore().preferences.activeGroup))

            return group ? group.name : trans('commons.all')
        },

        withoutTheAllGroup(state) {
            return state.items.filter(item => item.id > 0)
        },

        theAllGroup(state) {
            return state.items.find(item => item.id == 0)
        },

        isEmpty() {
            return this.withoutTheAllGroup.length == 0
        },

        count() {
            return this.withoutTheAllGroup.length
        },
    },

    actions: {
        /**
         * Adds or edits a group
         * @param {object} group 
         */
        addOrEdit(group) {
            const index = this.items.findIndex(g => g.id === parseInt(group.id))
            
            if (index > -1) {
                this.items[index] = group
                useNotifyStore().success({ text: trans('groups.group_name_saved') })
            }
            else {
                this.items.push(group)
                useNotifyStore().success({ text: trans('groups.group_successfully_created') })
            }            
        },

        /**
         * Fetches the groups collection from the backend
         */
        async fetch() {
            await groupService.getAll().then(response => {
                this.items = response.data
            })
        },

        /**
         * Deletes a group
         */
        async delete(id) {
            const user = useUserStore()

            if (confirm(trans('groups.confirm.delete'))) {
                await groupService.delete(id).then(response => {
                    this.items = this.items.filter(a => a.id !== id)
                    useNotifyStore().success({ text: trans('groups.group_successfully_deleted') })

                    // Reset group filter to 'All' (groupId=0) since the backend has already made
                    // the change automatically. This prevents a new request.
                    if( parseInt(user.preferences.activeGroup) === id ) {
                        user.preferences.activeGroup = 0
                    }
                })
            }
        },

    },
})
