<template>
    <section class="section">
        <div class="columns is-centered">
            <div class="column is-three-quarters">
                <div class="box has-text-centered has-background-black-ter ">
                    <figure class="image is-64x64" style="display: inline-block">
                        <img :src="icon">
                    </figure>
                    <p class="is-size-4 has-text-grey-light">{{ name }}</p>
                    <p class="is-size-6 has-text-grey">{{ email }}</p>
                    <slot></slot>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: ['name', 'email', 'icon'],
    }
</script>