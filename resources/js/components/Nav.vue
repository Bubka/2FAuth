<template>
    <nav class="navbar is-black level is-mobile has-text-grey-lighter">
        <div class="level-left">
            <p class="is-size-4 has-text-weight-light">2FAccount</p>
        </div>
        <div class="level-right">
            <p class="level-item">
                <a class="button is-black">
                    <span class="icon has-text-grey-light">
                        <font-awesome-icon :icon="['fas', 'plus-circle']" />
                    </span>
                </a>
            </p>
        </div>
    </nav>
</template>

<script>

    export default {
        name: 'twofa-nav'
    }

</script>