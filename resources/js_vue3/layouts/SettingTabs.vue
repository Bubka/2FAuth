<script setup>
    const tabs = ref([
        {
            'name' : 'settings.options',
            'view' : 'settings.options',
            'id'   : 'lnkTabOptions'
        },
        {
            'name' : 'settings.account',
            'view' : 'settings.account',
            'id'   : 'lnkTabAccount'
        },
        {
            'name' : 'settings.oauth',
            'view' : 'settings.oauth.tokens',
            'id'   : 'lnkTabOAuth'
        },
        {
            'name' : 'settings.webauthn',
            'view' : 'settings.webauthn.devices',
            'id'   : 'lnkTabWebauthn'
        },
    ])

    const props = defineProps({
        activeTab: {
            type: String,
            default: ''
        },
    })
</script>

<template>
    <div class="options-header">
        <ResponsiveWidthWrapper>
            <div class="tabs is-centered is-fullwidth">
                <ul>
                    <li v-for="tab in tabs" :key="tab.view" :class="{ 'is-active': tab.view === props.activeTab }">
                        <RouterLink :id="tab.id" :to="{ name: tab.view }">{{ $t(tab.name) }}</RouterLink>
                    </li>
                </ul>
            </div>
        </ResponsiveWidthWrapper>
    </div>
</template>