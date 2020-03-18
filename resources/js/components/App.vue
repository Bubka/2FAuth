<template>
    <div>
        <div v-if="$appSettings.isDemoApp" class="demo has-background-warning has-text-centered is-size-7-mobile">
            {{ $t('commons.demo_do_not_post_sensitive_data') }}
        </div>
        <main class="main-section">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    export default {
        name: 'App',
        
        data(){
            return {
            }
        }
    }
</script>