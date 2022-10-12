<template>
    <div class="columns is-centered">
        <div class="form-column column is-two-thirds-tablet is-half-desktop is-half-widescreen is-one-third-fullhd">
            <slot />
        </div>
    </div>    
</template>

<script>
    export default {
        name: 'ResponsiveWidthWrapper',
        
        data() {
            return {

            }
        },
    }
</script>