<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'

    const notify = useNotifyStore()
    const router = useRouter()
    
    const registerForm = reactive(new Form({
        name : '',
        email : '',
        password : '',
        password_confirmation : '',
        is_admin : false
    }))

    /**
     * Register a new user
     */
    async function createUser(e) {
        registerForm.password_confirmation = registerForm.password

        registerForm.post('/api/v1/users').then(response => {
            const user = response.data
            notify.success({ text: trans('admin.user_created') })
            router.push({ name: 'admin.manageUser', params: { userId: user.info.id } })
        })
    }
</script>

<template>
    <div>
        <FormWrapper title="admin.new_user">
            <form @submit.prevent="createUser" @keydown="registerForm.onKeydown($event)">
                <FormField v-model="registerForm.name" fieldName="name" :errorMessage="registerForm.errors.get('name')" inputType="text" label="auth.forms.name" autocomplete="username" :maxLength="255" autofocus />
                <FormField v-model="registerForm.email" fieldName="email" :errorMessage="registerForm.errors.get('email')" inputType="email" label="auth.forms.email" autocomplete="email" :maxLength="255" />
                <FormPasswordField v-model="registerForm.password" fieldName="password" :errorMessage="registerForm.errors.get('password')" :showRules="true" label="auth.forms.password" autocomplete="new-password" />
                <FormCheckbox v-model="registerForm.is_admin" fieldName="is_admin" label="admin.forms.is_admin.label" help="admin.forms.is_admin.help" />
                <FormButtons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" :showCancelButton="true" @cancel="router.push({ name: 'admin.users' })" submitLabel="commons.create" submitId="btnCreateUser" />
            </form>
        </FormWrapper>
        <!-- footer -->
        <VueFooter />
    </div>
</template>
