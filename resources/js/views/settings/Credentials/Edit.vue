<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'
    import { useI18n } from 'vue-i18n'

    const { t } = useI18n()
    const router = useRouter()
    const notify = useNotifyStore()
    const form = reactive(new Form({
        name: t('message.auth.webauthn.my_device')
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
            notify.success({ text: t('message.auth.webauthn.device_successfully_registered') })
            router.push({ name: 'settings.webauthn.devices' })
        })
    }

</script>

<template>
    <FormWrapper title="message.auth.webauthn.rename_device">
        <form @submit.prevent="updateCredential" @keydown="form.onKeydown($event)">
            <FormField v-model="form.name" fieldName="name" :errorMessage="form.errors.get('name')" inputType="text" label="message.new_name" autofocus />
            <FormButtons
                :submitId="'btnEditCredential'"
                :isBusy="form.isBusy"
                submitLabel="message.save"
                :showCancelButton="true"
                @cancel="router.push({ name: 'settings.webauthn.devices' })"
            />
        </form>
    </FormWrapper>
</template>