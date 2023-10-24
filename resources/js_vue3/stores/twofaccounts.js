import { defineStore } from 'pinia'
import { useUserStore } from '@/stores/user'
import { useNotifyStore } from '@/stores/notify'
import twofaccountService from '@/services/twofaccountService'
import { saveAs } from 'file-saver'

export const useTwofaccounts = defineStore({
    id: 'twofaccounts',

    state: () => {
        return {
            items: [],
            selectedIds: [],
            filter: '',
        }
    },

    getters: {
        filtered(state) {
            const user = useUserStore()

            return state.items.filter(
                item => {
                    if (parseInt(user.preferences.activeGroup) > 0 ) {
                        return ((item.service ? item.service.toLowerCase().includes(state.filter.toLowerCase()) : false) ||
                            item.account.toLowerCase().includes(state.filter.toLowerCase())) &&
                            (item.group_id == parseInt(user.preferences.activeGroup))
                    }
                    else {
                        return ((item.service ? item.service.toLowerCase().includes(state.filter.toLowerCase()) : false) ||
                            item.account.toLowerCase().includes(state.filter.toLowerCase()))
                    }
                }
            )
        },

        /**
         * Lists unique periods used by twofaccounts in the collection
         * ex: The items collection has 3 accounts with a period of 30s and 5 accounts with a period of 40s
         *     => The method will return [30, 40]
         */
        periods(state) {
            return state.items.filter(acc => acc.otp_type == 'totp').map(function(item) {
                return { period: item.period, generated_at: item.otp?.generated_at }
            }).filter((value, index, self) => index === self.findIndex((t) => (
                t.period === value.period
            ))).sort()
        },

        orderedIds(state) {
            return state.items.map(a => a.id)
        },

        isNotEmpty(state) {
            return state.items.length > 0
        },

        count(state) {
            return state.items.length
        },

        filteredCount(state) {
            return state.filtered.length
        },

        selectedCount(state) {
            return state.selectedIds.length
        },

        hasNoneSelected(state) {
            return state.selectedIds.length == 0
        },
    },

    actions: {

        /**
         * Refreshes the accounts collection using the backend
         */
        async refresh() {
            await twofaccountService.getAll(! useUserStore().preferences.getOtpOnRequest).then(response => {
                this.items = response.data
            })
        },

        /**
         * Adds an account to the current selection
         */
        select(id) {
            for (var i=0 ; i < this.selectedIds.length ; i++) {
                if ( this.selectedIds[i] === id ) {
                    this.selectedIds.splice(i,1);
                    return
                }
            }
            this.selectedIds.push(id)
        },

        /**
         * Selects all accounts
         */
        selectAll() {
            this.selectedIds = this.items.map(a => a.id)
        },

        /**
         * Selects no account
         */
        selectNone() {
            this.selectedIds = []
        },

        /**
         * Deletes selected accounts
         */
        async deleteSelected() {
            if(confirm(trans('twofaccounts.confirm.delete')) && this.selectedIds.length > 0) {
                await twofaccountService.batchDelete(this.selectedIds.join())
                .then(response => {
                    ids.forEach(function(id) {
                        this.items = this.items.filter(a => a.id !== id)
                    })
                    useNotifyStore().info({ text: trans('twofaccounts.accounts_deleted') })
                })

                this.refresh()
            }
        },

        /**
         * Exports selected accounts to a json file
         */
        export() {
            twofaccountService.export(this.selectedIds.join(), {responseType: 'blob'})
            .then((response) => {
                var blob = new Blob([response.data], {type: "application/json;charset=utf-8"});
                saveAs.saveAs(blob, "2fauth_export.json");
            })
        },

        /**
         * Saves the accounts order to db
         */
        saveOrder() {
            twofaccountService.saveOrder(this.orderedIds)
        },
        
        /**
         * Sorts accounts ascending
         */
        sortAsc() {
            this.items.sort((a, b) => a.service > b.service ? 1 : -1)
            this.saveOrder()
        },

        /**
         * Sorts accounts descending
        */
        sortDesc() {
            this.items.sort((a, b) => a.service < b.service ? 1 : -1)
            this.saveOrder()
        },
        
        /**
         * Gets the IDs of all accounts that match the given period
         * @param {*} period 
         * @returns {Array<Number>} IDs of matching accounts
         */
        accountIdsWithPeriod(period) {
            return this.items.filter(a => a.period == period).map(item => item.id)
        },
    },
})
