<template>
    <div class="options-header">
        <responsive-width-wrapper>
            <div class="tabs is-centered is-fullwidth">
                <ul>
                    <li v-for="tab in tabs" :key="tab.view" :class="{ 'is-active': tab.view === activeTab }">
                        <router-link :id="tab.id" :to="{ name: tab.view }">{{ tab.name }}</router-link>
                    </li>
                </ul>
            </div>
        </responsive-width-wrapper>
    </div>
</template>

<script>

    export default {
        name: 'SettingTabs',

        data(){
            return {
                tabs: [
                	{
                		'name' : this.$t('settings.options'),
                        'view' : 'settings.options',
                        'id'   : 'lnkTabOptions'
                	},
                	{
                		'name' : this.$t('settings.account'),
                        'view' : 'settings.account',
                        'id'   : 'lnkTabAccount'
                	},
                	{
                		'name' : this.$t('settings.oauth'),
                        'view' : 'settings.oauth.tokens',
                        'id'   : 'lnkTabOAuth'
                	},
                	{
                		'name' : this.$t('settings.webauthn'),
                        'view' : 'settings.webauthn.devices',
                        'id'   : 'lnkTabWebauthn'
                	},
            	]
            }
        },

        props: {
            activeTab: {
                type: String,
                default: ''
            },
        },
    }

</script>