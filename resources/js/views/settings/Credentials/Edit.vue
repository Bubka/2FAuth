<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotify } from '@2fauth/ui'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const router = useRouter()
    const notify = useNotify()
    const form = reactive(new Form({
        name: t('label.my_device')
    }))

    const props = defineProps({
        credentialId: {
            type: String,
            default: ''
        },
    })

    function updateCredential() {
        form.patch('/webauthn/credentials/' + props.credentialId + '/name')
        .then(() => {
            notify.success({ text: t('notification.device_successfully_registered') })
            router.push({ name: 'settings.webauthn.devices' })
        })
    }

</script>

<template>
    <FormWrapper title="heading.rename_device">
        <form @submit.prevent="updateCredential" @keydown="form.onKeydown($event)">
            <FormField v-model="form.name" fieldName="name" :errorMessage="form.errors.get('name')" inputType="text" label="field.new_name" autofocus />
            <FormButtons
                :submitId="'btnEditCredential'"
                :isBusy="form.isBusy"
                submitLabel="label.save"
                :showCancelButton="true"
                @cancel="router.push({ name: 'settings.webauthn.devices' })"
            />
        </form>
    </FormWrapper>
</template>