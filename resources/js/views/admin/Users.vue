<script setup>
    import AdminTabs from '@/layouts/AdminTabs.vue'
    import userService from '@/services/userService'
    import { useNotifyStore } from '@/stores/notify'
    import { UseColorMode } from '@vueuse/components'
    import Spinner from '@/components/Spinner.vue'
    import SearchBox from '@/components/SearchBox.vue'

    const $2fauth = inject('2fauth')
    const notify = useNotifyStore()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')

    const users = ref([])
    const searched = ref('')
    const isFetching = ref(false)

    const visibleUsers = computed(() => {
        return users.value.filter(
            user => {
                let ret = user.name.toLowerCase().includes(searchedObj.value.keywords)
                    || user.email.toLowerCase().includes(searchedObj.value.keywords)

                if (searchedObj.value.admin != undefined) {
                    ret = ret && user.is_admin == searchedObj.value.admin
                }
                if (searchedObj.value.oauth != undefined) {
                    ret = ret && user.oauth_provider == searchedObj.value.oauth
                }

                return ret
            }
        )
    })

    const searchedObj = computed(() => {
        const obj = {
            admin: undefined,
            oauth: undefined,
            keywords: searched.value.toLowerCase()
        }
        const searchedChunks = searched.value.toLowerCase().split(' ')
        const regexAdmin = /admin:([01])/
        const regexOAuth = /oauth:([a-zA-Z0-9])/

        searchedChunks.forEach(chunk => {
            if (chunk.match(regexAdmin)) {
                obj.admin = parseInt(chunk.replace(regexAdmin, '$1'))
                obj.keywords = obj.keywords.replace(chunk, '').trim()
            }
            if (chunk.match(regexOAuth)) {
                obj.oauth = chunk.replace(regexOAuth, '$1')
                obj.keywords = obj.keywords.replace(chunk, '').trim()
            }
        })

        return obj
    })

    onMounted(() => {
        fetchUsers()
    })

    /**
     * 
     * @param {*} filter 
     */
    function addFilter(filter) {
        const regexAdmin = /admin:([01])/
        const regexOAuth = /oauth:([a-zA-Z0-9]*)/

        if (searched.value.match(regexAdmin) && filter.match(regexAdmin)) {
            searched.value = searched.value.replace(regexAdmin, filter)
        }
        else if (searchedObj.value.oauth != undefined && filter.match(regexOAuth)) {
            searched.value = searched.value.replace(regexOAuth, filter)
        }
        else searched.value = searched.value ? searched.value + ' ' + filter : filter
    }

    /**
     * Gets all users from backend
     */
    function fetchUsers() {
        isFetching.value = true

        userService.getAll({returnError: true})
        .then(response => {
            users.value = response.data
        })
        .catch(error => {
            notify.error(error)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    /**
     * Deletes a user
     */
    function deleteUser(userId) {
        if(confirm(trans('admin.confirm.delete_user'))) {
            // TODO
        }
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('admin.')) {
            notify.clear()
        }
    })

</script>

<template>
    <div>
        <AdminTabs activeTab="admin.users" />
        <div class="options-tabs">
            <FormWrapper>
                <h4 class="title is-4 has-text-grey-light">{{ $t('admin.users') }}</h4>
                <div class="is-size-7-mobile">
                    {{ $t('admin.users_legend')}}
                </div>
                <div class="mb-6 mt-3">
                    <RouterLink class="is-link mt-5" :to="{ name: 'admin.users.create' }">
                        <FontAwesomeIcon :icon="['fas', 'plus-circle']" /> {{ $t('admin.create_new_user') }}
                    </RouterLink>
                </div>
                <!-- search -->
                <div class="columns">
                    <div class="column pb-0">
                        <SearchBox v-model:keyword="searched" :hasNoBackground="true" :placeholder="$t('admin.search_user_placeholder')" />
                    </div>
                </div>
                <div class="level is-mobile mb-0">
                    <div class="level-item has-text-centered is-justify-content-end">
                        <p class="subtitle is-7">
                            {{ $t('admin.quick_filters_colons') }}
                        </p>
                    </div>
                    <div class="level-item has-text-centered is-justify-content-start">
                        <div class="buttons">
                            <button class="button is-small is-ghost p-0" @click="addFilter('admin:1')">admin</button>
                            <button class="button is-small is-ghost p-0" @click="addFilter('oauth:github')">github</button>
                            <button class="button is-small is-ghost p-0" @click="addFilter('oauth:openid')">openId</button>
                        </div>
                    </div>
                </div>
                <div v-if="visibleUsers.length > 0">
                    <div v-for="user in visibleUsers" :key="user.id" class="list-item is-size-5 is-size-6-mobile is-flex is-justify-content-space-between">
                        <div class="has-ellipsis">
                            <span>{{ user.name }}</span>
                            <!-- <FontAwesomeIcon v-if="token.value" class="has-text-success" :icon="['fas', 'check']" /> {{ token.name }} -->
                            <!-- set as admin link -->
                            <!-- admin tag -->
                            <span class="is-block has-ellipsis is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ user.email }}</span>
                            <!-- tag -->
                            <div class="tags mt-2">
                                <span v-if="user.is_admin" class="tag is-rounded has-background-black-bis has-text-warning-dark">admin</span>
                                <span v-if="user.oauth_provider" class="tag is-rounded has-background-black-bis has-text-grey">oauth: {{ user.oauth_provider }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="tags ml-3">
                                <UseColorMode v-slot="{ mode }">
                                    <!-- manage link -->
                                    <RouterLink :to="{ name: 'admin.users.manage', params: { userId: user.id }}" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark' : 'is-white'" :title="$t('commons.manage')">
                                        {{ $t('commons.manage') }}
                                    </RouterLink>
                                </UseColorMode>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('settings.revoking_a_token_is_permanent')}}
                    </div> -->
                </div>
                <div v-else class="mt-4 pl-3">
                    {{ $t('commons.no_result') }}
                </div>
                <Spinner :isVisible="isFetching && users.length === 0" />
                <!-- footer -->
                <VueFooter :showButtons="true">
                    <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
                </VueFooter>
            </FormWrapper>
        </div>
    </div>
</template>