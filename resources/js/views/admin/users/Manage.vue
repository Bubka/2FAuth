<script setup>
    import CopyButton from '@/components/CopyButton.vue'
    import AccessLogViewer from '@/components/AccessLogViewer.vue'
    import userService from '@/services/userService'
    import { useNotify } from '@2fauth/ui'
    import { UseColorMode } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { useBusStore } from '@/stores/bus'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const notify = useNotify()
    const router = useRouter()
    const user = useUserStore()
    const bus = useBusStore()
    const $2fauth = inject('2fauth')

    const isFetching = ref(false)
    const managedUser = ref(null)
    const listUserPreferences = ref(null)
    const showFullLogLink = ref(false)

    const props = defineProps({
        userId: [Number, String]
    })

    onMounted(async () => {
        await getUser()
    })

    /**
     * Gets the user from backend
     */
    async function getUser() {
        isFetching.value = true

        userService.getById(props.userId, {returnError: true})
        .then(response => {
            managedUser.value = response.data
            bus.username = managedUser.value.info.name
        })
        .catch(error => {
            errorHandler.show(error)
        })
        .finally(() => {
            isFetching.value = false
        })
    }

    /**
     * Resends a pwd reset email to the user
     */
    async function resendPasswordEmail() {
        if (! confirmForYourself()) {
            return false
        }
        
        if (confirm(t('confirmation.purge_password_reset_request')) === true) {
            await userService.resendPasswordEmail(managedUser.value.info.id)
            managedUser.value.password_reset = null
        }
    }

    /**
     * Resets the user password
     */
    async function resetPassword() {
        if (! confirmForYourself()) {
            return false
        }

        if (confirm(t('confirmation.request_password_reset')) === true) {
            userService.resetPassword(managedUser.value.info.id, { returnError: true })
            .then(response => {
                managedUser.value = response.data
                notify.success({ text: t('notification.password_successfully_reset') })
            })
            .catch(error => {
                if(error.response.status === 400) {
                    notify.alert({ text: error.response.data.reason })
                }
                else {
                    errorHandler.show(error)
                }
            })
        }
    }

    /**
     * Set admin role
     * 
     * @param {string} preference 
     * @param {boolean} isAdmin 
     */
    function saveAdminRole(isAdmin) {
        if (! confirm(t('confirmation.change_admin_role'))) {
            nextTick().then(() => {
                    managedUser.value.info.is_admin = ! isAdmin
                })
            return
        }

        if(isAdmin === false && managedUser.value.info.id === user.id) {
            if (! confirm(t('confirmation.demote_own_account'))) {
                nextTick().then(() => {
                    managedUser.value.info.is_admin = true
                })
                return
            }
        }

        userService.promote(managedUser.value.info.id, { 'is_admin': isAdmin }, { returnError: true }).then(response => {
            managedUser.value.info.is_admin = response.data.info.is_admin
            notify.success({ text: t('notification.user_role_updated') })
        })
        .catch(error => {
            if( error.response.status === 403 ) {
                notify.alert({ text: error.response.data.message })
                managedUser.value.info.is_admin = true
            }
            else {
                errorHandler.show(error)
            }
        })
    }

    /**
     * submit user account deletion
     */
    function deleteUser() {
        if (! confirmForYourself()) {
            return false
        }

        if(confirm(t('confirmation.delete_user'))) {
            userService.delete(managedUser.value.info.id, { returnError: true }).then(response => {
                notify.success({ text: t('notification.user_account_successfully_deleted') })
                router.push({ name: 'admin.users' });
            })
            .catch(error => {
                if( error.response.status === 403 ) {
                    notify.alert({ text: error.response.data.message })
                }
                else {
                    errorHandler.show(error)
                }
            })
        }
    }

    /**
     * submit user account deletion
     */
    function revokePATs() {
        if (! confirmForYourself()) {
            return false
        }

        userService.revokePATs(managedUser.value.info.id).then(response => {
            managedUser.value.valid_personal_access_tokens = 0
            notify.success({ text: t('notification.pats_succesfully_revoked') })
        })
    }

    /**
     * submit user account deletion
     */
    function revokeWebauthnCredentials() {
        if (! confirmForYourself()) {
            return false
        }

        userService.revokeWebauthnCredentials(managedUser.value.info.id).then(response => {
            managedUser.value.valid_personal_access_tokens = 0
            notify.success({ text: t('notification.security_devices_succesfully_revoked') })
        })
    }

    /**
     * Confirmation for modification on own account
     */
    function confirmForYourself() {
        if(managedUser.value.info.id === user.id) {
            if (! confirm(t('confirmation.edit_own_account'))) {
                return false
            }
        }
        return true
    }
    
</script>


<template>
    <UseColorMode v-slot="{ mode }">
    <ResponsiveWidthWrapper>
        <h1 class="title has-text-grey-dark mb-6">
            {{ $t('heading.user_management') }}
        </h1>
        <!-- loader -->
        <Spinner v-if="isFetching || ! managedUser" :isVisible="true" type="fullscreen-overlay" message="message.fetching_data" />
        <!-- user info -->
        <div v-else>
            <div class="mb-6" :class="managedUser.info.is_admin ? 'is-left-bordered-warning' : 'is-left-bordered-link'">
                <p class="title is-4" :class="{ 'has-text-grey-lighter' : mode == 'dark' }">
                <span class="has-text-weight-light has-text-grey-dark is-pulled-right">#{{ managedUser.info.id }}</span>{{ managedUser.info.name }}</p>
                <p class="subtitle is-6 block">{{ managedUser.info.email }}</p>
            </div>
            <!-- oauth banner -->
            <div v-if="managedUser.info.oauth_provider" class="notification is-dark is-size-7-mobile has-text-centered">
                {{ $t('message.account_bound_to_x_via_oauth', { provider: managedUser.info.oauth_provider }) }}
            </div>
            <div class="block is-size-6 is-size-7-mobile has-text-grey">
                {{ $t('message.registered_on_date', { date: managedUser.info.created_at }) }} - {{ $t('message.last_seen_on_date', { date: managedUser.info.last_seen_at }) }}
            </div>
            <!-- isAdmin option -->
            <div class="block">
                <FormCheckbox v-model="managedUser.info.is_admin" @update:model-value="val => saveAdminRole(val === true)" fieldName="is_admin" label="field.is_admin" help="field.is_admin.help" />
            </div>
            <h2 v-if="!$2fauth.config.proxyAuth" class="title is-4 has-text-grey-light">{{ $t('heading.access') }}</h2>
            <!-- access -->
            <div v-if="!$2fauth.config.proxyAuth" class="block">
                <!-- reset password -->
                <div class="list-item is-size-6 is-size-6-mobile has-text-grey">
                    <div class="mb-3 is-flex is-justify-content-space-between">
                        <div>
                            <span class="has-text-weight-bold">{{ $t('heading.password') }}</span>
                        </div>
                        <div>
                            <div class="tags ml-3 is-right">
                                <!-- resend email button -->
                                <button type="button" v-if="managedUser.password_reset" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark has-background-link' : 'is-white'" @click="resendPasswordEmail" :title="$t('tooltip.resend_email_title')">
                                    {{ $t('label.resend_email') }}
                                </button>
                                <!-- reset password button -->
                                <button type="button" class="button tag is-pulled-right " :class="mode == 'dark' ? 'is-dark has-background-link' : 'is-white'" @click="resetPassword" :title="$t('tooltip.reset_password_title')">
                                    {{ $t('label.reset_password') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="is-size-7 is-size-7-mobile has-text-grey-dark">
                        <span v-if="managedUser.password_reset === 0" class="is-block block">{{ $t('message.password_request_expired') }}</span>
                        <span v-else-if="managedUser.password_reset" class="is-block block">{{ $t('message.password_requested_on_t', { datetime: managedUser.password_reset }) }}</span>
                        <span v-if="managedUser.password_reset" class="is-block block">{{ $t('message.resend_email_help') }}</span>
                        <span class="is-block block">{{ $t('message.reset_password_help') }}</span>
                    </div>
                </div>
                <!-- personal access tokens -->
                <div class="list-item is-size-6 is-size-6-mobile has-text-grey is-flex is-justify-content-space-between">
                    <div>
                        <span class="has-text-weight-bold">{{ $t('heading.personal_access_tokens') }}</span>
                        <span class="is-block is-family-primary has-text-grey-dark">
                            {{ $t('message.user_has_x_active_pat', { count: managedUser.valid_personal_access_tokens }) }}
                        </span>
                    </div>
                    <div v-if="managedUser.valid_personal_access_tokens > 0">
                        <div class="tags ml-3 is-right">
                            <!-- manage link -->
                            <button type="button" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark has-background-link' : 'is-white'" @click="revokePATs" :title="$t('tooltip.revoke_all_pat_for_user')">
                                {{ $t('label.revoke') }}
                            </button>
                        </div>
                    </div>
                </div>
                <!-- webauthn devices -->
                <div class="list-item is-size-6 is-size-6-mobile has-text-grey is-flex is-justify-content-space-between">
                    <div>
                        <span class="has-text-weight-bold">{{ $t('heading.security_devices') }}</span>
                        <span class="is-block has-text-grey-dark">
                            {{ $t('message.user_has_x_security_devices', { count: managedUser.webauthn_credentials }) }}
                        </span>
                    </div>
                    <div v-if="managedUser.webauthn_credentials > 0">
                        <div class="tags ml-3 is-right">
                            <!-- manage link -->
                            <button type="button" class="button tag is-pulled-right" :class="mode == 'dark' ? 'is-dark has-background-link' : 'is-white'" :title="$t('tooltip.revoke_all_devices_for_user')">
                                {{ $t('label.revoke') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- last access -->
            <div class="block">
                <h3 class="title is-5 has-text-grey-light mb-2">{{ $t('heading.last_accesses') }}</h3>
                <AccessLogViewer v-if="managedUser" :userId="props.userId" :lastOnly="true" @has-more-entries="showFullLogLink = true"/>
            </div>
            <div v-if="showFullLogLink" class="block is-size-6 is-size-7-mobile has-text-grey">
                {{ $t('message.access_log_has_more_entries') }} <router-link id="lnkFullLogs" :to="{ name: 'admin.logs.access', params: { userId: props.userId }}" >
                    {{ $t('link.see_full_log') }}.
                </router-link>
            </div>
            <!-- preferences -->
            <h2 class="title is-4 has-text-grey-light">{{ $t('heading.preferences') }}</h2>
            <div class="about-debug box is-family-monospace is-size-7">
                <CopyButton id="btnCopyEnvVars" :token="listUserPreferences?.innerText" />
                <ul ref="listUserPreferences" id="listUserPreferences">
                    <li v-for="(value, preference) in managedUser.info.preferences" :value="value" :key="preference">
                        <b>{{preference}}</b>: <span class="has-text-grey">{{value}}</span>
                    </li>
                </ul>
            </div>
            <!-- danger zone -->
            <h2 class="title is-4 has-text-danger">{{ $t('heading.danger_zone') }}</h2>
            <div class="is-left-bordered-danger">
                <div class="block is-size-6 is-size-7-mobile">
                    {{  $t('message.delete_this_user_legend') }}
                    <span class="is-block has-text-grey has-text-weight-bold">
                        {{  $t('message.this_is_not_soft_delete') }}
                    </span>
                </div>
                <button type="button" class="button is-danger" @click="deleteUser" title="delete">
                    {{  $t('label.delete_this_user') }}
                </button>
            </div>
        </div>
        <!-- footer -->
        <VueFooter>
            <template #default>
                <NavigationButton action="back" @goback="router.push({ name: 'admin.users' })" :previous-page-title="$t('title.admin.users')" />
                <NavigationButton action="close" @closed="router.push({ name: 'accounts' })" :current-page-title="$t('title.admin.manageUser')" />
            </template>
        </VueFooter>
    </ResponsiveWidthWrapper>
    </UseColorMode>
</template>
