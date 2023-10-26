<script setup>
    import Form from '@/components/formElements/Form'
    import { useNotifyStore } from '@/stores/notify'

    const router = useRouter()
    const notify = useNotifyStore()
    const form = reactive(new Form({
        name: trans('auth.webauthn.my_device')
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
            notify.success({ text: trans('auth.webauthn.device_successfully_registered') })
            router.push({ name: 'settings.webauthn.devices' })
        })
    }

</script>

<template>
    <FormWrapper title="auth.webauthn.rename_device">
        <form @submit.prevent="updateCredential" @keydown="form.onKeydown($event)">
            <FormField v-model="form.name" fieldName="name" :fieldError="form.errors.get('name')" inputType="text" label="commons.new_name" autofocus />
            <FormButtons
                :submitId="'btnEditCredential'"
                :isBusy="form.isBusy"
                :caption="$t('commons.save')"
                :showCancelButton="true"
                cancelLandingView="settings.webauthn.devices"
            />
        </form>
    </FormWrapper>
</template>