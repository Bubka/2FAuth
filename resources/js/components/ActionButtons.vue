<script setup>
    import { UseColorMode  } from '@vueuse/components'
    import { useUserStore } from '@/stores/user'
    import { LucideTrash2, LucideScanQrCode, LucideFolderDown, LucideDownload, LucideUsers, LucideShapes, LucideGroup } from '@lucide/vue'

    const router = useRouter()
    const user = useUserStore()

    const props = defineProps({
        inManagementMode: {
            type: Boolean,
            default: false
        },
        canMove: {
            type: Boolean,
            default: true
        },
        canDelete: {
            type: Boolean,
            default: true
        },
        canShare: {
            type: Boolean,
            default: true
        },
        canExport: {
            type: Boolean,
            default: true
        },
        areDisabled: {
            type: Boolean,
            default: false
        },
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
            <button type="button" class="button is-link is-rounded is-focus" @click="goAddNewAccount">
                <span>{{ $t('label.new') }}</span>
                <span class="icon is-small">
                    <LucideScanQrCode />
                </span>
            </button>
        </p>
        <!-- Manage button -->
        <p class="control" v-if="!inManagementMode">
            <button type="button" id="btnManage" class="button is-rounded" :class="{'is-dark' : mode == 'dark'}" @click="$emit('update:inManagementMode', true)">{{ $t('label.manage') }}</button>
        </p>
        <!-- move button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnMove" 
                :disabled='areDisabled || !canMove' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('move-button-clicked')"
                :title="$t('tooltip.move_selected_to_group')" >
                <span class="is-hidden-desktop icon is-small mx-0">
                    <LucideGroup />
                </span>
                <span class="is-hidden-touch">{{ $t('label.move') }}</span>
            </button>
        </p>
        <!-- share button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnShare" 
                :disabled='areDisabled || !canShare' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('share-button-clicked')"
                :title="$t('tooltip.share_selected_accounts')" >
                <span class="is-hidden-desktop icon is-small mx-0">
                    <LucideUsers />
                </span>
                <span class="is-hidden-touch">{{ $t('label.share') }}</span>
            </button>
        </p>
        <!-- delete button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnDelete" 
                :disabled='areDisabled || !canDelete' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('delete-button-clicked')"
                :title="$t('tooltip.delete_selected_accounts')" >
                    <span class="is-hidden-desktop icon is-small mx-0">
                        <LucideTrash2 />
                    </span>
                    <span class="is-hidden-touch">{{ $t('label.delete') }}</span>
            </button>
        </p>
        <!-- export button -->
        <p class="control" v-if="inManagementMode">
            <button
                id="btnExport" 
                :disabled='areDisabled || !canExport' class="button is-rounded"
                :class="[{ 'is-outlined': mode == 'dark' || areDisabled }, areDisabled ? 'is-dark': 'is-link']"
                @click="$emit('export-button-clicked')"
                :title="$t('tooltip.export_selected_accounts')" >
                <span class="is-hidden-desktop icon is-small mx-0">
                    <LucideDownload />
                </span>
                <span class="is-hidden-touch">{{ $t('label.export') }}</span>
            </button>
        </p>
    </UseColorMode>
</template>