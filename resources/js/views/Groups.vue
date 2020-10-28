<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
            <h1 class="title">
                {{ $t('groups.groups') }}
            </h1>
            <p class="is-size-7-mobile">
                {{ $t('groups.manage_groups_legend')}}
            </p>
            <router-link class="is-link" :to="{ name: 'createGroup' }">
                <font-awesome-icon :icon="['fas', 'plus-circle']" /> Create new group
            </router-link>
            <div v-for="group in groups" :key="group.id" class="group-item has-text-light is-size-5 is-size-6-mobile">
                {{ group.name }}
                <a class="has-text-grey is-pulled-right" @click="deleteGroup(group.id)">
                    <font-awesome-icon :icon="['fas', 'trash']" />
                </a>
                <router-link :to="{ name: 'editGroup', params: { groupId: group.id }}" class="tag is-dark">
                    {{ $t('commons.rename') }}
                </router-link>
                <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ group.count }} {{ $t('twofaccounts.accounts') }}</span>
            </div>
            <p class="is-size-7 is-pulled-right" v-if="groups.length > 0">
                {{ $t('groups.deleting_group_does_not_delete_accounts')}}
            </p>
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
            }
        },

        mounted() {

            this.fetchGroups()
        },

        methods: {

            async fetchGroups() {

                await this.axios.get('api/groups').then(response => {
                    response.data.forEach((data) => {
                        this.groups.push({
                            id : data.id,
                            name : data.name,
                            count: data.twofaccounts_count
                        })
                    })
                })

                // Remove the pseudo 'All' group
                this.groups.shift()
            },

            deleteGroup(id) {
                if(confirm(this.$t('groups.confirm.delete'))) {
                    this.axios.delete('/api/groups/' + id)

                    // Remove the deleted group from the collection
                    this.groups = this.groups.filter(a => a.id !== id)
                }
            }

        },

    }
</script>