<script setup>
    import Form from '@/components/formElements/Form'
    import groupService from '@/services/groupService'
    import { useGroups } from '@/stores/groups'
    import { useBusStore } from '@/stores/bus'

    const groups = useGroups()
    const router = useRouter()
    const route = useRoute()
    const bus = useBusStore()
    const $2fauth = inject('2fauth')
    const returnTo = useStorage($2fauth.prefix + 'returnTo', 'groups')

    const props = defineProps({
        groupId: [Number, String]
    })

    const isEditMode = computed(() => {
        return props.groupId != undefined
    })
    
    const form = reactive(new Form({
        name: '',
        show_in_chips: false
    }))

    onBeforeMount(() => {
        // We get the name to edit from the bus store to prevent request latency
        if (route.name == 'editGroup') {
            if (bus.editedGroupName) {
                form.name = bus.editedGroupName
                bus.editedGroupName = undefined
            }
            
            groupService.get(props.groupId).then(response => {
                form.name = response.data.name
                form.show_in_chips = response.data.show_in_chips
            })
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
            router.push({ name: returnTo.value })
        })
    }

    /**
     * Submits the form to the backend to update the group
     */
    async function updateGroup() {
        form.put('/api/v1/groups/' + props.groupId).then(response => {
            groups.addOrEdit(response.data)
            router.push({ name: returnTo.value })
        })
    }

</script>

<template>
    <StackLayout>
        <template #content>
            <FormWrapper :title="isEditMode ? 'heading.edit_group' : 'heading.new_group'">
                <form @submit.prevent="handleSubmit" @keydown="form.onKeydown($event)">
                    <FormField v-model="form.name" fieldName="name" :errorMessage="form.errors.get('name')" label="field.name" autofocus />
                    <FormCheckbox v-model="form.show_in_chips" fieldName="show_in_chips" label="field.show_in_chips" help="field.show_in_chips.help" />
                    <FormButtons
                        :submitId="isEditMode ? 'btnEditGroup' : 'btnCreateGroup'"
                        :isBusy="form.isBusy"
                        :submitLabel="isEditMode ? 'label.save' : 'label.create'"
                        :showCancelButton="true"
                        @cancel="router.push({ name: returnTo })" />
                </form>
            </FormWrapper>
        </template>
    </StackLayout>
</template>