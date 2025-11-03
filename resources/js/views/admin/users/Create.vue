<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotify } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const notify = useNotify()
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
            notify.success({ text: t('notification.user_created') })
            router.push({ name: 'admin.manageUser', params: { userId: user.info.id } })
        })
    }
</script>

<template>
    <div>
        <FormWrapper title="heading.new_user">
            <form @submit.prevent="createUser" @keydown="registerForm.onKeydown($event)">
                <FormField v-model="registerForm.name" fieldName="name" :errorMessage="registerForm.errors.get('name')" inputType="text" label="field.name" autocomplete="username" :maxLength="255" autofocus />
                <FormField v-model="registerForm.email" fieldName="email" :errorMessage="registerForm.errors.get('email')" inputType="email" label="field.email" autocomplete="email" :maxLength="255" />
                <FormPasswordField v-model="registerForm.password" fieldName="password" :errorMessage="registerForm.errors.get('password')" :showRules="true" label="field.password" autocomplete="new-password" />
                <FormCheckbox v-model="registerForm.is_admin" fieldName="is_admin" label="field.is_admin" help="field.is_admin.help" />
                <FormButtons :isBusy="registerForm.isBusy" :isDisabled="registerForm.isDisabled" :showCancelButton="true" @cancel="router.push({ name: 'admin.users' })" submitLabel="label.create" submitId="btnCreateUser" />
            </form>
        </FormWrapper>
        <!-- footer -->
        <VueFooter />
    </div>
</template>
