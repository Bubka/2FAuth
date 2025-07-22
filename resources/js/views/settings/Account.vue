<script setup>
    import tabs from './tabs'
    import Form from '@/components/formElements/Form'
    import { useUserStore } from '@/stores/user'
    import { useNotify, TabBar } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'
    import { useErrorHandler } from '@2fauth/stores'

    const errorHandler = useErrorHandler()
    const { t } = useI18n()
    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotify()
    const router = useRouter()
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'accounts')
    
    const formProfile = reactive(new Form({
        name : user.name,
        email : user.email,
        password : '',
    }))
    const formPassword = reactive(new Form({
        currentPassword : '',
        password : '',
        password_confirmation : '',
    }))
    const formDelete = reactive(new Form({
        password : '',
    }))

    /**
     * submit user profile changes
     */
    function submitProfile(e) {

        formProfile.put('/user', {returnError: true})
        .then(response => {
            user.$patch({
                name: response.data.name,
                email: response.data.email,
                isAdmin: response.data.is_admin,
            })
            notify.success({ text: t('notification.profile_saved') })
        })
        .catch(error => {
            if( error.response.status === 400 ) {
                notify.alert({ text: error.response.data.message })
            }
            else if( error.response.status !== 422 ) {
                errorHandler.show(error)
            }
        })
    }

    /**
     * submit password change
     */
     function submitPassword(e) {

        formPassword.patch('/user/password', {returnError: true})
        .then(response => {
            formPassword.password = ''
            formPassword.formPassword = ''
            formPassword.password_confirmation = ''
            notify.success({ text: response.data.message })
        })
        .catch(error => {
            if( error.response.status === 400 ) {
                notify.alert({ text: error.response.data.message })
            }
            else if( error.response.status !== 422 ) {
                errorHandler.show(error)
            }
        })
    }

    /**
     * submit user account deletion
     */
     function submitDelete(e) {

        if(confirm(t('confirmation.delete_account'))) {
            formDelete.delete('/user', {returnError: true})
            .then(response => {
                notify.success({ text: t('notification.user_account_successfully_deleted') })
                router.push({ name: 'register' });
            })
            .catch(error => {
                if( error.response.status === 400 ) {
                    notify.alert({ text: error.response.data.message })
                }
                else if( error.response.status !== 422 ) {
                    errorHandler.show(error)
                }
            })
        }
    }

    onBeforeRouteLeave((to) => {
        if (! to.name.startsWith('settings.') && to.name === 'login') {
            notify.clear()
        }
    })
</script>

<template>
    <div>
        <TabBar :tabs="tabs" :active-tab="'settings.account'" @tab-selected="(to) => router.push({ name: to })" />
        <div class="options-tabs">
            <FormWrapper>
                <div v-if="user.isAdmin" class="notification is-warning">
                    {{ $t('message.you_are_administrator') }}
                </div>
                <div v-if="user.oauth_provider" class="notification is-info has-text-centered">
                    {{ $t('message.account_linked_to_sso_x_provider', { provider: user.oauth_provider }) }}
                </div>
                <form @submit.prevent="submitProfile" @keydown="formProfile.onKeydown($event)">
                    <div v-if="$2fauth.config.proxyAuth" class="notification is-warning has-text-centered">
                        {{ $t('message.user_account_controlled_by_proxy') + ' ' + $t('message.manage_account_at_proxy_level') }}
                    </div>
                    <h4 class="title is-4 has-text-grey-light">{{ $t('heading.profile') }}</h4>
                    <fieldset :disabled="$2fauth.config.proxyAuth || user.oauth_provider">
                        <FormField v-model="formProfile.name" fieldName="name" :errorMessage="formProfile.errors.get('name')" label="field.name" :maxLength="255" autocomplete="username" autofocus />
                        <FormField v-model="formProfile.email" fieldName="email" :errorMessage="formProfile.errors.get('email')" inputType="email" label="field.email" autocomplete="email" :maxLength="255" autofocus />
                        <FormField v-model="formProfile.password" fieldName="password" :errorMessage="formProfile.errors.get('password')" inputType="password" label="field.current_password" autocomplete="current-password" help="field.current_password.help" />
                        <FormButtons :isBusy="formProfile.isBusy" submitLabel="label.update" />
                    </fieldset>
                </form>
                <form @submit.prevent="submitPassword" @keydown="formPassword.onKeydown($event)">
                    <input hidden type="text" name="name" :value="formProfile.name" autocomplete="username" />
                    <input hidden type="text" name="email" :value="formProfile.email" autocomplete="email" />
                    <h4 class="title is-4 pt-6 has-text-grey-light">{{ $t('heading.change_password') }}</h4>
                    <fieldset :disabled="$2fauth.config.proxyAuth || user.oauth_provider">
                        <FormPasswordField v-model="formPassword.password" fieldName="password" :errorMessage="formPassword.errors.get('password')" idSuffix="ForUpdate" autocomplete="new-password" :showRules="true" label="field.new_password" />
                        <FormPasswordField v-model="formPassword.password_confirmation" :showRules="false" fieldName="password_confirmation" :errorMessage="formPassword.errors.get('password_confirmation')" inputType="password" autocomplete="new-password" label="field.confirm_new_password" />
                        <FormField v-model="formPassword.currentPassword" fieldName="currentPassword" :errorMessage="formPassword.errors.get('currentPassword')" inputType="password" label="field.current_password" autocomplete="current-password" help="field.current_password.help" />
                        <FormButtons :isBusy="formPassword.isBusy" submitId="btnSubmitChangePwd" submitLabel="label.change_password" />
                    </fieldset>
                </form>
                <form id="frmDeleteAccount" @submit.prevent="submitDelete" @keydown="formDelete.onKeydown($event)">
                    <input hidden type="text" name="name" :value="formProfile.name" autocomplete="username" />
                    <input hidden type="text" name="email" :value="formProfile.email" autocomplete="email" />
                    <h4 class="title is-4 pt-6 has-text-danger">{{ $t('heading.delete_account') }}</h4>
                    <div class="field is-size-7-mobile">
                        <p class="block">{{ $t('message.delete_your_account_and_reset_all_data')}}</p>
                        <p>{{ $t('message.reset_your_password_to_delete_your_account') }}</p>
                        <p>{{ $t('message.deleting_2fauth_account_does_not_impact_provider') }}</p>
                    </div>
                    <fieldset :disabled="$2fauth.config.proxyAuth">
                        <FormField v-model="formDelete.password" fieldName="password" :errorMessage="formDelete.errors.get('password')" inputType="password" idSuffix="ForDelete" autocomplete="new-password" label="field.current_password" help="field.current_password.help" />
                        <FormButtons :isBusy="formDelete.isBusy" submitLabel="label.delete_your_account" submitId="btnDeleteAccount" color="is-danger" />
                    </fieldset>
                </form>
            </FormWrapper>
        </div>
        <VueFooter>
            <template #default>
                <NavigationButton action="close" @closed="router.push({ name: returnTo })" :current-page-title="$t('title.settings.account')" />
            </template>
        </VueFooter>
    </div>
</template>
