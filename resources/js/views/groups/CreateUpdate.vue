<script setup>
    import Form from '@/components/formElements/Form'
    import groupService from '@/services/groupService'
    import { useGroups } from '@/stores/groups'
    import { useBusStore } from '@/stores/bus'

    const groups = useGroups()
    const router = useRouter()
    const route = useRoute()
    const bus = useBusStore()

    const props = defineProps({
        groupId: [Number, String]
    })

    const isEditMode = computed(() => {
        return props.groupId != undefined
    })
    
    const form = reactive(new Form({
        name: '',
    }))

    onBeforeMount(() => {
        // We get the name to edit from the bus store to prevent request latency
        if (route.name == 'editGroup') {
            if (bus.editedGroupName) {
                form.name = bus.editedGroupName
                bus.editedGroupName = undefined
            }
            else {
                groupService.get(props.groupId).then(response => {
                    form.name = response.data.name
                })
            }
        }
    })

    /**
     * Wrapper to call the appropriate function at form submit
     */
    function handleSubmit() {
        isEditMode.value ? updateGroup() : createGroup()
    }

    /**
     * Submits the form to the backend to store the new group
     */
    async function createGroup() {
        form.post('/api/v1/groups').then(response => {
            groups.addOrEdit(response.data)
            router.push({ name: 'groups' })
        })
    }

    /**
     * Submits the form to the backend to update the group
     */
    async function updateGroup() {
        form.put('/api/v1/groups/' + props.groupId).then(response => {
            groups.addOrEdit(response.data)
            router.push({ name: 'groups' })
        })
    }

</script>

<template>
    <FormWrapper :title="isEditMode ? 'heading.rename_group' : 'heading.new_group'">
        <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
            <FormField v-model="form.name" fieldName="name" :errorMessage="form.errors.get('name')" label="field.name" autofocus />
            <FormButtons
                :submitId="isEditMode ? 'btnEditGroup' : 'btnCreateGroup'"
                :isBusy="form.isBusy"
                :submitLabel="isEditMode ? 'label.save' : 'label.create'"
                :showCancelButton="true"
                @cancel="router.push({ name: 'groups' })" />
        </form>
    </FormWrapper>
</template>