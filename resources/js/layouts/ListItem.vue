<script setup>

</script>

<template>
    <div class="list-item is-size-5 is-size-6-mobile is-flex is-justify-content-space-between">
        <div>
            <slot name="item"></slot>
            <slot name="legend"></slot>
        </div>
        <div>
            <div class="tags ml-3">
                <slot name="buttons"></slot>
            </div>
        </div>
    </div>
</template>