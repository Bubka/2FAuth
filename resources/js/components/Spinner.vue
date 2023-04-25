<template>
    <div v-if="active" class="spinner-container">
        <div class="spinner-wrapper">
            <span class="is-size-1 spinner">
                <font-awesome-icon :icon="['fas', 'spinner']" spin />
            </span>
            <span>{{ message }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
    props: {
        active: {
            type: Boolean,
            default: false
        },
        message: String,
    }
}
</script>

<style scoped>
.spinner-container {
    text-align: center;
    z-index: 10000;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.spinner {
    display: block;
}
</style>
