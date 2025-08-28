<script setup>
    import tabs from './tabs'
    import userService from '@/services/userService'
    import { useNotify, TabBar, SearchBox } from '@2fauth/ui'
    import { UseColorMode } from '@vueuse/components'
    import { useErrorHandler } from '@2fauth/stores'
    import { LucideCirclePlus } from 'lucide-vue-next'

    const errorHandler = useErrorHandler()
    const $2fauth = inject('2fauth')
    const notify = useNotify()
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
            errorHandler.show(error)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('admin.')) {
            notify.clear()
        }
    })

</script>

<template>
    <div>
        <TabBar :tabs="tabs" :active-tab="'admin.users'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <h4 class="title is-4 has-text-grey-light">{{ $t('heading.users') }}</h4>
                <div class="is-size-7-mobile">
                    {{ $t('message.admin_users_legend')}}
                </div>
                <div class="mb-6 mt-3">
                    <RouterLink class="is-link mt-5" :to="{ name: 'admin.createUser' }">
                        <LucideCirclePlus /> {{ $t('link.create_new_user') }}
                    </RouterLink>
                </div>
                <!-- search -->
                <div class="columns">
                    <div class="column pb-0">
                        <SearchBox v-model:keyword="searched" :hasNoBackground="true" :placeholder="$t('placeholder.search_user_placeholder')" />
                    </div>
                </div>
                <div class="level is-mobile mb-0">
                    <div class="level-item has-text-centered is-justify-content-end">
                        <p class="subtitle is-7">
                            {{ $t('message.quick_filters_colons') }}
                        </p>
                    </div>
                    <div class="level-item has-text-centered is-justify-content-start">
                        <div class="buttons">
                            <button type="button" class="button is-small is-ghost p-0" @click="addFilter('admin:1')">admin</button>
                            <button type="button" class="button is-small is-ghost p-0" @click="addFilter('oauth:github')">github</button>
                            <button type="button" class="button is-small is-ghost p-0" @click="addFilter('oauth:openid')">openId</button>
                        </div>
                    </div>
                </div>
                <div v-if="visibleUsers.length > 0">
                    <div v-for="user in visibleUsers" :key="user.id" class="list-item is-size-5 is-size-6-mobile is-flex is-justify-content-space-between">
                        <UseColorMode v-slot="{ mode }">
                            <div class="has-ellipsis">
                                <span>{{ user.name }}</span>
                                <!-- set as admin link -->
                                <!-- admin tag -->
                                <span class="is-block has-ellipsis is-family-primary is-size-6 is-size-7-mobile has-text-grey">{{ user.email }}</span>
                                <!-- tag -->
                                <div class="tags mt-2">
                                    <span v-if="user.is_admin" class="tag is-rounded has-text-warning-dark" :class="mode == 'dark' ? 'has-background-black-bis' : 'has-background-grey-lighter'" >admin</span>
                                    <span v-if="user.oauth_provider" class="tag is-rounded  has-text-grey" :class="mode == 'dark' ? 'has-background-black-bis' : 'has-background-grey-lighter'" >oauth: {{ user.oauth_provider }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <!-- manage link -->
                                <RouterLink :to="{ name: 'admin.manageUser', params: { userId: user.id }}" class="button is-small has-normal-radius" :class="{'is-dark' : mode == 'dark'}" :title="$t('tooltip.manage')">
                                    {{ $t('link.manage') }}
                                </RouterLink>

                            </div>
                        </UseColorMode>
                    </div>
                    <!-- <div class="mt-2 is-size-7 is-pulled-right">
                        {{ $t('message.revoking_a_token_is_permanent')}}
                    </div> -->
                </div>
                <Spinner v-else-if="isFetching" :isVisible="true" type="list-loading" />
                <div v-else class="mt-4 pl-3">
                    {{ $t('message.no_result') }}
                </div>
                <!-- footer -->
                <VueFooter>
                    <template #default>
                        <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.admin.users')" />
                    </template>
                </VueFooter>
            </FormWrapper>
        </div>
    </div>
</template>