<template>
    <form-wrapper :title="$t('groups.forms.new_group')">
        <form @submit.prevent="createGroup" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('commons.name')" autofocus />
            <form-buttons
                :submitId="'btnCreateGroup'"
                :isBusy="form.isBusy"
                :caption="$t('commons.create')"
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
                    name: '',
                })
            }
        },

        methods: {

            async createGroup() {

                await this.form.post('/api/v1/groups')

                if( this.form.errors.any() === false ) {
                    this.$notify({ type: 'is-success', text: this.$t('groups.group_successfully_created') })
                    this.$router.push({ name: 'groups' });
                }

            },
            
        },

    }
</script>