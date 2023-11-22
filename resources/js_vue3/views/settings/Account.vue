<script setup>
    import Form from '@/components/formElements/Form'
    import SettingTabs from '@/layouts/SettingTabs.vue'
    import { useUserStore } from '@/stores/user'
    import { useNotifyStore } from '@/stores/notify'

    const $2fauth = inject('2fauth')
    const user = useUserStore()
    const notify = useNotifyStore()
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
            notify.success({ text: trans('auth.forms.profile_saved') })
        })
        .catch(error => {
            if( error.response.status === 400 ) {
                notify.alert({ text: error.response.data.message })
            }
            else if( error.response.status !== 422 ) {
                notify.error(error.response)
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
                notify.error(error.response)
            }
        })
    }

    /**
     * submit user account deletion
     */
     function submitDelete(e) {

        if(confirm(trans('auth.confirm.delete_account'))) {
            formDelete.delete('/user', {returnError: true})
            .then(response => {
                notify.success({ text: trans('auth.forms.user_account_successfully_deleted') })
                router.push({ name: 'register' });
            })
            .catch(error => {
                if( error.response.status === 400 ) {
                    notify.alert({ text: error.response.data.message })
                }
                else if( error.response.status !== 422 ) {
                    notify.error(error.response)
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
        <SettingTabs :activeTab="'settings.account'" />
        <div class="options-tabs">
            <FormWrapper>
                <div v-if="user.isAdmin" class="notification is-warning">
                    {{ $t('settings.you_are_administrator') }}
                </div>
                <form @submit.prevent="submitProfile" @keydown="formProfile.onKeydown($event)">
                    <div v-if="$2fauth.config.proxyAuth" class="notification is-warning has-text-centered" v-html="$t('auth.user_account_controlled_by_proxy')" />
                    <h4 class="title is-4 has-text-grey-light">{{ $t('settings.profile') }}</h4>
                    <fieldset :disabled="$2fauth.config.proxyAuth">
                        <FormField v-model="formProfile.name" fieldName="name" :fieldError="formProfile.errors.get('name')" label="auth.forms.name" :maxLength="255" autofocus />
                        <FormField v-model="formProfile.email" fieldName="email" :fieldError="formProfile.errors.get('email')" inputType="email" label="auth.forms.email" :maxLength="255" autofocus />
                        <FormField v-model="formProfile.password" fieldName="password" :fieldError="formProfile.errors.get('password')" inputType="password" label="auth.forms.current_password.label" help="auth.forms.current_password.help" />
                        <FormButtons :isBusy="formProfile.isBusy" caption="commons.update" />
                    </fieldset>
                </form>
                <form @submit.prevent="submitPassword" @keydown="formPassword.onKeydown($event)">
                    <h4 class="title is-4 pt-6 has-text-grey-light">{{ $t('settings.change_password') }}</h4>
                    <fieldset :disabled="$2fauth.config.proxyAuth">
                        <FormPasswordField v-model="formPassword.password" fieldName="password" :fieldError="formPassword.errors.get('password')" :autocomplete="'new-password'" :showRules="true" label="auth.forms.new_password" />
                        <FormPasswordField v-model="formPassword.password_confirmation" :showRules="false" fieldName="password_confirmation" :fieldError="formPassword.errors.get('password_confirmation')" inputType="password" :autocomplete="'new-password'" label="auth.forms.confirm_new_password" />
                        <FormField v-model="formPassword.currentPassword" fieldName="currentPassword" :fieldError="formPassword.errors.get('currentPassword')" inputType="password" label="auth.forms.current_password.label" help="auth.forms.current_password.help" />
                        <FormButtons :isBusy="formPassword.isBusy" caption="auth.forms.change_password" />
                    </fieldset>
                </form>
                <form id="frmDeleteAccount" @submit.prevent="submitDelete" @keydown="formDelete.onKeydown($event)">
                    <h4 class="title is-4 pt-6 has-text-danger">{{ $t('auth.forms.delete_account') }}</h4>
                    <div class="field is-size-7-mobile">
                        {{ $t('auth.forms.delete_your_account_and_reset_all_data')}}
                    </div>
                    <fieldset :disabled="$2fauth.config.proxyAuth">
                        <FormField v-model="formDelete.password" fieldName="password" :fieldError="formDelete.errors.get('password')" inputType="password" autocomplete="new-password" label="auth.forms.current_password.label" help="auth.forms.current_password.help" />
                        <FormButtons :isBusy="formDelete.isBusy" caption="auth.forms.delete_your_account" submitId="btnDeleteAccount" color="is-danger" />
                    </fieldset>
                </form>
            </FormWrapper>
        </div>
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel :returnTo="{ name: returnTo }" action="close" />
        </VueFooter>
    </div>
</template>
