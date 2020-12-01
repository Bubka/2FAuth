<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
            <h1 class="title">
                {{ $t('groups.groups') }}
            </h1>
            <div class="is-size-7-mobile">
                {{ $t('groups.manage_groups_legend')}}
            </div>
            <div class="mt-3 mb-6">
                <router-link class="is-link mt-5" :to="{ name: 'createGroup' }">
                    <font-awesome-icon :icon="['fas', 'plus-circle']" /> Create new group
                </router-link>
            </div>
            <div v-if="groups.length > 0">
                <div v-for="group in groups" :key="group.id" class="group-item has-text-light is-size-5 is-size-6-mobile">
                    {{ group.name }}
                    <!-- delete icon -->
                    <a class="has-text-grey is-pulled-right" @click="deleteGroup(group.id)">
                        <font-awesome-icon :icon="['fas', 'trash']" />
                    </a>
                    <!-- edit link -->
                    <router-link :to="{ name: 'editGroup', params: { id: group.id, name: group.name }}" class="tag is-dark">
                        {{ $t('commons.rename') }}
                    </router-link>
                    <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ group.count }} {{ $t('twofaccounts.accounts') }}</span>
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
                    <router-link  :to="{ name: 'accounts' }" class="button is-dark is-rounded" @click="">{{ $t('commons.close') }}</router-link>
                </p>
            </vue-footer>
        </div>
    </div>
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

            async fetchGroups() {

                this.isFetching = true

                await this.axios.get('api/groups').then(response => {
                    const groups = []

                    response.data.forEach((data) => {
                        groups.push({
                            id : data.id,
                            name : data.name,
                            count: data.twofaccounts_count
                        })
                    })

                    // Remove the pseudo 'All' group
                    this.TheAllGroup = groups.shift()

                    this.groups = groups
                })

                this.isFetching = false
            },

            deleteGroup(id) {
                if(confirm(this.$t('groups.confirm.delete'))) {
                    this.axios.delete('/api/groups/' + id)

                    // Remove the deleted group from the collection
                    this.groups = this.groups.filter(a => a.id !== id)

                    // Reset persisted group filter to 'All' (groupId=0)
                    if( parseInt(this.$root.appSettings.activeGroup) === id ) {
                        this.axios.post('/api/settings/options', { activeGroup: 0 }).then(response => {
                            this.$root.appSettings.activeGroup = 0
                        })
                    }
                }
            }

        },

        beforeRouteLeave(to, from, next) {
            this.groups.unshift(this.TheAllGroup)
            // Refresh localstorage
            this.$storage.set('groups', this.groups)

            next()
        }

    }
</script>