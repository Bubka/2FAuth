import { defineStore } from 'pinia'
import { useUserStore } from '@/stores/user'
import { useNotify } from '@2fauth/ui'
import twofaccountService from '@/services/twofaccountService'
import { saveAs } from 'file-saver'

export const useTwofaccounts = defineStore('twofaccounts', {
    state: () => {
        return {
            items: [],
            selectedIds: [],
            filter: '',
            backendWasNewer: false,
            fetchedOn: null,
            isFetching: false,
            pagination: null
        }
    },

    getters: {
        filtered(state) {
            const user = useUserStore()

            return state.items.filter(
                item => {
                    let itemMatch = false

                    // Group filters :
                    // -1: group less items
                    // -2: items shared by me
                    // -3: items shared with me
                    //  0: all items (no group filter)
                    // >0: any concrete group

                    // group less items
                    if (parseInt(user.preferences.activeGroup) == -1 && item.group_id == null) {
                        itemMatch = true
                    }
                    // items I share
                    else if (parseInt(user.preferences.activeGroup) == -2 && (item.is_shared == true || item.is_shared_with_all == true)) {
                        itemMatch = true
                    }
                    // items shared with me
                    else if (parseInt(user.preferences.activeGroup) == -3 && item.is_borrowed == true) {
                        itemMatch = true
                    }
                    else if (parseInt(user.preferences.activeGroup) > 0 && item.group_id == parseInt(user.preferences.activeGroup)) {
                        // no global filter but a group
                        itemMatch = true
                    }
                    else if (parseInt(user.preferences.activeGroup) == 0) {
                        // All items are matching
                        itemMatch = true
                    }

                    if (state.filter) {
                        itemMatch = itemMatch && (
                            (item.service ? item.service.toLowerCase().includes(state.filter.toLowerCase()) : false)
                            || item.account.toLowerCase().includes(state.filter.toLowerCase())
                        )
                    }

                    return itemMatch
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

        previousPage(state) {
            return state.pagination
                ? state.pagination.current_page - 1
                : null
        },

        nextPage(state) {
            return state.pagination
                ? state.pagination.current_page + 1
                : null
        },

        orderedIds(state) {
            return state.items.map(a => a.id)
        },

        isEmpty(state) {
            return state.items.length == 0
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

        hasBorrowedSelected(state) {
            return state.items.some(a => state.selectedIds.includes(a.id) && a.is_borrowed)
        },

        hasOnlyBorrowedSelected(state) {
            return state.selectedIds.length > 0 && state.selectedIds.every(id => {
                const account = state.items.find(a => a.id === id)
                return account?.is_borrowed === true
            })
        },

        hasSharedSelected(state) {
            return state.items.some(a => state.selectedIds.includes(a.id) && (a.is_shared == true || a.is_shared_with_all == true))
        },

        hasOnlySharedSelected(state) {
            return state.selectedIds.length > 0 && state.selectedIds.every(id => {
                const account = state.items.find(a => a.id === id)
                return account?.is_shared === true || account?.is_shared_with_all === true
            })
        }
    },

    actions: {

        /**
         * Refreshes the accounts collection using the backend
         */
        async fetch(pageNumber = null,force = false) {
            // We do not want to fetch fresh data multiple times in the same 2s timespan
            const age = Math.floor(Date.now() - this.fetchedOn)
            const isOutOfAge = age > 2000
            const user = useUserStore()

            pageNumber = user.preferences.usePagination && pageNumber ? pageNumber : null

            if (isOutOfAge || force || (pageNumber && this.pagination && pageNumber != this.pagination.current_page)) {
                this.isFetching = true
                this.fetchedOn = Date.now()


                await twofaccountService.getAll(! user.preferences.getOtpOnRequest, pageNumber).then(response => {
                    let fetchedAccounts

                    if (response.data.meta) {
                        this.pagination = {
                            from: response.data.meta.from,
                            to: response.data.meta.to,
                            total: response.data.meta.total,
                            per_page: response.data.meta.per_page,
                            current_page: response.data.meta.current_page,
                            last_page: response.data.meta.last_page,
                        }
                        fetchedAccounts = response.data.data
                    }
                    else {
                        this.pagination = null
                        fetchedAccounts = response.data
                    }

                    // Defines if the store was up-to-date with the backend
                    if (force) {
                        this.backendWasNewer = fetchedAccounts.length !== this.items.length
                        
                        this.items.forEach((item) => {
                            let matchingBackendItem = fetchedAccounts.find(e => e.id === item.id)
                            if (matchingBackendItem == undefined) {
                                this.backendWasNewer = true
                                return;
                            }
                            for (const field in item) {
                                if (field !== 'otp' && item[field] != matchingBackendItem[field]) {
                                    this.backendWasNewer = true
                                    return;
                                }
                            }
                        })
                    }
    
                    // Updates the state
                    this.items = fetchedAccounts
                })
                .finally(() => {
                    this.isFetching = false
                })
            }
            else this.backendWasNewer = false
        },

        /**
         * Get a 2FA account by its ID
         */
        getById(id) {
            return this.items.find(a => a.id == id)
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
            if(confirm(this.$i18n.global.t('confirmation.delete_twofaccount')) && this.selectedIds.length > 0) {
                await twofaccountService.batchDelete(this.selectedIds.join())
                .then(response => {
                    let remainingItems = this.items
                    this.selectedIds.forEach(function(id) {
                        remainingItems = remainingItems.filter(a => a.id !== id)
                    })
                    this.items = remainingItems
                    this.selectNone()
                    useNotify().success({ text: this.$i18n.global.t('notification.accounts_deleted') })
                })
            }
        },

        /**
         * Exports selected accounts to a json file
         */
        export(format = '2fauth') {
            if (format == 'otpauth') {
                twofaccountService.export(this.selectedIds.join(), true)
                .then((response) => {
                    let uris = []
                    response.data.data.forEach(account => {
                        uris.push(account.uri)
                    });
                    var blob = new Blob([uris.join('\n')], {type: "text/plain;charset=utf-8"});
                    saveAs.saveAs(blob, "2fauth_export_otpauth.txt");
                })
            }
            else {
                twofaccountService.export(this.selectedIds.join(), false, {responseType: 'blob'})
                .then((response) => {
                    var blob = new Blob([response.data], {type: "application/json;charset=utf-8"});
                    saveAs.saveAs(blob, "2fauth_export.json");
                })
            }
        },

        /**
         * Saves the accounts order to db
         */
        saveOrder(newOrder = 'free') {
            twofaccountService.saveOrder(this.orderedIds).then(() => {
                useUserStore().preferences.sortOrder = newOrder
                userService.updatePreference('sortOrder', newOrder)
            })
        },
        
        /**
         * Sorts accounts based on user default option
         */
        sortDefault() {
            if (useUserStore().preferences.sortOrder == 'asc') {
                this.sortAsc()
            }

            if (useUserStore().preferences.sortOrder == 'desc') {
                this.sortDesc()
            }
        },
        
        /**
         * Sorts accounts ascending
         */
        sortAsc() {
            this.items.sort(function(a, b) {
                const serviceA = a.service ?? ''
                const serviceB = b.service ?? ''

                if (useUserStore().preferences.sortCaseSensitive) {
                    return serviceA.normalize("NFD").replace(/[\u0300-\u036f]/g, "") > serviceB.normalize("NFD").replace(/[\u0300-\u036f]/g, "") ? 1 : -1
                }
                
                return serviceA.localeCompare(serviceB, useUserStore().preferences.lang)
            });

            this.saveOrder('asc')
        },

        /**
         * Sorts accounts descending
        */
        sortDesc() {
            this.items.sort(function(a, b) {
                const serviceA = a.service ?? ''
                const serviceB = b.service ?? ''

                if (useUserStore().preferences.sortCaseSensitive) {
                    return serviceA.normalize("NFD").replace(/[\u0300-\u036f]/g, "") < serviceB.normalize("NFD").replace(/[\u0300-\u036f]/g, "") ? 1 : -1
                }

                return serviceB.localeCompare(serviceA, useUserStore().preferences.lang)
            });

            this.saveOrder('desc')
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
