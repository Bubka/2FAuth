<template>
    <form-wrapper :title="$t('groups.forms.rename_group')">
        <form @submit.prevent="updateGroup" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('groups.forms.new_name')" autofocus />
            <div class="field is-grouped">
                <div class="control">
                    <v-button :isLoading="form.isBusy">{{ $t('commons.save') }}</v-button>
                </div>
                <div class="control">
                    <button type="button" class="button is-text" @click="cancelCreation">{{ $t('commons.cancel') }}</button>
                </div>
            </div>
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

                await this.form.put('/api/groups/' + this.id)

                if( this.form.errors.any() === false ) {
                    this.$router.push({ name: 'groups' })
                }

            },

            cancelCreation: function() {

                this.$router.push({ name: 'groups' });
            },

        },

    }
</script>