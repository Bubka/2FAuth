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
                groupExists: false,
                form: new Form({
                    name: '',
                })
            }
        },

        created: function() {
            this.getGroup();
        },

        methods: {
            async getGroup () {

                const { data } = await this.axios.get('/api/groups/' + this.$route.params.groupId)

                this.form.fill(data)
                this.groupExists = true
                
            },

            async updateGroup() {

                await this.form.put('/api/groups/' + this.$route.params.groupId)

                if( this.form.errors.any() === false ) {
                    this.$router.push({name: 'groups', params: { InitialEditMode: true }})
                }

            },

            cancelCreation: function() {

                this.$router.push({name: 'groups', params: { InitialEditMode: true }});
            },

        },

    }
</script>