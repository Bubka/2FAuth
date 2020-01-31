<template>
    <div>
        <!-- toolbar -->
        <header class="toolbar has-background-black-bis" v-if="showToolbar">
            <div class="container">
                <div class="columns is-mobile is-centered">
                    <div class="column is-three-quarters-mobile is-one-third-tablet is-one-quarter-desktop is-one-quarter-widescreen is-one-quarter-fullhd">
                        <a class="button is-dark">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <!-- main content -->
        <main class="main-section">
            <router-view :showToolbar="showToolbar"></router-view>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'App',
        
        data(){
            return {
                showToolbar: false
            }
        }
    }
</script>