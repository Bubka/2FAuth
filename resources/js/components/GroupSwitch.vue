<script setup>
    import userService from '@/services/userService'
    import { useUserStore } from '@/stores/user'
    import { UseColorMode } from '@vueuse/components'

    const user = useUserStore()
    const props = defineProps({
        showGroupSwitch: Boolean,
        groups: Array
    })

    const emit = defineEmits(['update:showGroupSwitch'])

    /**
     * Sets the selected group as the active group
     */
    function setActiveGroup(id) {
        user.preferences.activeGroup = id

        if( user.preferences.rememberActiveGroup ) {
            userService.updatePreference('activeGroup', id)
        }
        
        emit('update:showGroupSwitch', false)
    }

</script>

<template>
    <div id="groupSwitch" class="container groups">
        <div class="columns is-centered">
            <div class="column is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                <div class="columns is-multiline">
                    <div class="column is-full" v-for="group in groups" :key="group.id">
                        <UseColorMode v-slot="{ mode }">
                            <button type="button" class="button is-fullwidth" :class="{'is-dark has-text-light is-outlined': mode == 'dark'}" @click="setActiveGroup(group.id)">{{ group.name }}</button>
                        </UseColorMode>
                    </div>
                </div>
                <div class="columns is-centered">
                    <div class="column has-text-centered">
                        <RouterLink :to="{ name: 'groups' }" >{{ $t('groups.manage_groups') }}</RouterLink>
                    </div>
                </div>
            </div>
        </div>
        <VueFooter :showButtons="true">
            <ButtonBackCloseCancel action="close" :useLinkTag="false" @closed="$emit('update:showGroupSwitch', false)" />
        </VueFooter>
    </div>
</template>