<script setup>
    const { notify }  = useNotification()

    const props = defineProps({
        modelValue: Boolean,
        closable: {
            type: Boolean,
            default: true
        },
    })

    const emit = defineEmits(['update:modelValue'])

    const isActive = computed({
        get() {
            return props.modelValue
        },
        set(value) {
            emit('update:modelValue', value)
        }
    })

    function closeModal(event) {
        notify({ clean: true })
        isActive.value = false
    }
</script>

<template>
    <div class="modal modal-otp" v-bind:class="{ 'is-active': isActive }">
        <div class="modal-background" @click.stop="closeModal"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="modal-slot box has-text-centered is-shadowless">
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <VueFooter v-if="props.closable" :showButtons="true" :internalFooterType="'modal'">
            <ButtonBackCloseCancel action="close" :useLinkTag="false" @closed="closeModal" />
        </VueFooter>
    </div>
</template>