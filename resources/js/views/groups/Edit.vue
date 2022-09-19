<template>
    <form-wrapper :title="$t('groups.forms.rename_group')">
        <form @submit.prevent="updateGroup" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('groups.forms.new_name')" autofocus />
            <form-buttons
                :submitId="'btnEditGroup'"
                :isBusy="form.isBusy"
                :caption="$t('commons.save')"
                :showCancelButton="true"
                cancelLandingView="groups" />
        </form>
    </form-wrapper>
</template>

<script>

    import Form from './../../components/Form'

    export default {
        data() {
            return {
                form: new Form({
                    name: this.name,
                })
            }
        },

        props: ['id', 'name'],

        methods: {

            async updateGroup() {

                await this.form.put('/api/v1/groups/' + this.id)

                if( this.form.errors.any() === false ) {
                    this.$notify({ type: 'is-success', text: this.$t('groups.group_name_saved') })
                    this.$router.push({ name: 'groups' })
                }

            },

        },

    }
</script>