<template>
    <form-wrapper :title="$t('groups.forms.new_group')">
        <form @submit.prevent="createGroup" @keydown="form.onKeydown($event)">
            <form-field :form="form" fieldName="name" inputType="text" :label="$t('commons.name')" autofocus />
            <div class="field is-grouped">
                <div class="control">
                    <v-button>{{ $t('commons.create') }}</v-button>
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
                    name: '',
                })
            }
        },

        methods: {

            async createGroup() {

                await this.form.post('/api/groups')

                if( this.form.errors.any() === false ) {
                    this.$router.push({ name: 'groups' });
                }

            },

            cancelCreation: function() {

                this.$router.push({ name: 'groups' });
            },
            
        },

    }
</script>