<template>
    <responsive-width-wrapper>
        <h1 class="title has-text-grey-dark">
            {{ $t('groups.groups') }}
        </h1>
        <div class="is-size-7-mobile">
            {{ $t('groups.manage_groups_legend')}}
        </div>
        <div class="mt-3 mb-6">
            <router-link class="is-link mt-5" :to="{ name: 'createGroup' }">
                <font-awesome-icon :icon="['fas', 'plus-circle']" /> {{ $t('groups.create_group') }}
            </router-link>
        </div>
        <div v-if="groups.length > 0">
            <div v-for="group in groups" :key="group.id" class="group-item is-size-5 is-size-6-mobile">
                {{ group.name }}
                <!-- delete icon -->
                <button class="button tag is-pulled-right" :class="$root.showDarkMode ? 'is-dark' : 'is-white'" @click="deleteGroup(group.id)"  :title="$t('commons.delete')">
                    {{ $t('commons.delete') }}
                </button>
                <!-- edit link -->
                <router-link :to="{ name: 'editGroup', params: { id: group.id, name: group.name }}" class="has-text-grey px-1" :title="$t('commons.rename')">
                    <font-awesome-icon :icon="['fas', 'pen-square']" />
                </router-link>
                <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ group.twofaccounts_count }} {{ $t('twofaccounts.accounts') }}</span>
            </div>
            <div class="mt-2 is-size-7 is-pulled-right" v-if="groups.length > 0">
                {{ $t('groups.deleting_group_does_not_delete_accounts')}}
            </div>
        </div>
        <div v-if="isFetching && groups.length === 0" class="has-text-centered">
            <span class="is-size-4">
                <font-awesome-icon :icon="['fas', 'spinner']" spin />
            </span>
        </div>
        <!-- footer -->
        <vue-footer :showButtons="true">
            <!-- close button -->
            <p class="control">
                <router-link  :to="{ name: 'accounts', params: { toRefresh: true } }" class="button is-rounded" :class="{'is-dark' : $root.showDarkMode}">{{ $t('commons.close') }}</router-link>
            </p>
        </vue-footer>
    </responsive-width-wrapper>
</template>

<script>

    export default {
        data() {
            return {
                groups : [],
                TheAllGroup : null,
                isFetching: false,
            }
        },

        mounted() {
            // Load groups for localstorage at first to avoid latency
            const groups = this.$storage.get('groups', null) // use null as fallback if localstorage is empty
            
            // We don't want the pseudo group 'All' to be managed so we shift it
            if( groups ) {
                this.groups = groups
                this.TheAllGroup = this.groups.shift()
            }

            // we refresh the collection whatever
            this.fetchGroups()
        },

        methods: {

            /**
             * Get all groups from backend
             */
            async fetchGroups() {

                this.isFetching = true

                await this.axios.get('api/v1/groups').then(response => {
                    const groups = []

                    response.data.forEach((data) => {
                        groups.push(data)
                    })

                    // Remove the 'All' pseudo group from the collection
                    // and push it the TheAllGroup
                    this.TheAllGroup = groups.shift()

                    this.groups = groups
                })

                this.isFetching = false
            },

            /**
             * Delete a group (after confirmation)
             */
            async deleteGroup(id) {
                if(confirm(this.$t('groups.confirm.delete'))) {
                    await this.axios.delete('/api/v1/groups/' + id).then(response => {
                        // Remove the deleted group from the collection
                        this.groups = this.groups.filter(a => a.id !== id)
                        this.$notify({ type: 'is-success', text: this.$t('groups.group_successfully_deleted') })

                        // Reset persisted group filter to 'All' (groupId=0)
                        // (backend will save to change automatically)
                        if( parseInt(this.$root.userPreferences.activeGroup) === id ) {
                            this.$root.userPreferences.activeGroup = 0
                        }
                    })
                }
            }

        },

        beforeRouteLeave(to, from, next) {
            // reinject the 'All' pseudo group before refreshing the localstorage
            this.groups.unshift(this.TheAllGroup)
            this.$storage.set('groups', this.groups)

            next()
        }

    }
</script>