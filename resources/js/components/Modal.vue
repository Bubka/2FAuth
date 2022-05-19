<template>
    <div class="modal modal-otp" v-bind:class="{ 'is-active': isActive }">
        <div class="modal-background" @click.stop="closeModal"></div>
        <div class="modal-content">
            <section class="section">
                <div class="columns is-centered">
                    <div class="column is-three-quarters">
                        <div class="box has-text-centered has-background-black-ter is-shadowless">
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div v-if="this.showcloseButton" class="fullscreen-footer">
            <!-- Close button -->
            <label class="button is-dark is-rounded" @click.stop="closeModal">
                {{ $t('commons.close') }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Modal',

    data(){
        return {
            showcloseButton: this.closable,
        }
    },

    props: {
        value: Boolean,
        closable: {
            type: Boolean,
            default: true
        },
    },

    computed: {
        isActive: {
            get () {
                return this.value
            },
            set (value) {
                this.$emit('input', value)
            }
        }
    },

    methods: {
        closeModal: function(event) {
            if (event) {
                this.isActive = false
                this.$notify({ clean: true })
                this.$parent.$emit('modalClose')
            }
        }
    }
}
</script>
