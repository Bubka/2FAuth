<script setup>
    import { UseColorMode  } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'

    const router = useRouter()
    const user = useUserStore()

    const props = defineProps({
        inManagementMode: Boolean,
        areDisabled: Boolean
    }) 
    
    const emit = defineEmits(['update:inManagementMode', 'move-button-clicked', 'delete-button-clicked', 'export-button-clicked'])

    /**
     * Routes user to the appropriate submitting view
     */
    function goAddNewAccount() {
        if( user.preferences.useDirectCapture && user.preferences.defaultCaptureMode === 'advancedForm' ) {
            router.push({ name: 'createAccount' })
        }
        else if( user.preferences.useDirectCapture && user.preferences.defaultCaptureMode === 'livescan' ) {
            router.push({ name: 'capture' })
        }
        else {
            router.push({ name: 'start' })
        }
    }
</script>

<template>
    <UseColorMode v-slot="{ mode }">
        <!-- New item buttons -->
        <p class="control" v-if="!inManagementMode">
            <button class="button is-link is-rounded is-focus" @click="goAddNewAccount">
                <span>{{ $t('commons.new') }}</span>
                <span class="icon is-small">
                    <FontAwesomeIcon :icon="['fas', 'qrcode']" />
                </span>
            </button>
        </p>
        <!-- Manage button -->
        <p class="control" v-if="!inManagementMode">
            <button id="btnManage" class="button is-rounded" :class="{'is-dark' : mode == 'dark'}" @click="$emit('update:inManagementMode', true)">{{ $t('commons.manage') }}</button>
        </p>
        <!-- move button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnMove" 
                :disabled='areDisabled' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('move-button-clicked')"
                :title="$t('groups.move_selected_to_group')" >
                    {{ $t('commons.move') }}
            </button>
        </p>
        <!-- delete button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnDelete" 
                :disabled='areDisabled' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('delete-button-clicked')" >
                    {{ $t('commons.delete') }}
            </button>
        </p>
        <!-- export button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnExport" 
                :disabled='areDisabled' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('export-button-clicked')"
                :title="$t('twofaccounts.export_selected_accounts')" >
                    {{ $t('commons.export') }}
            </button>
        </p>
    </UseColorMode>
</template>