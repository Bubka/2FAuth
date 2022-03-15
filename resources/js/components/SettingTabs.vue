<template>
    <div class="options-header has-background-black-ter">
        <div class="columns is-centered">
            <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
                <div class="tabs is-centered is-fullwidth">
                    <ul>
                        <li v-for="tab in tabs" :key="tab.view" :class="{ 'is-active': tab.view === activeTab }">
                            <a @click="selectTab(tab.view)">{{ tab.name }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
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
                        'view' : 'settings.options'
                	},
                	{
                		'name' : this.$t('settings.account'),
                        'view' : 'settings.account'
                	},
                	{
                		'name' : this.$t('settings.oauth'),
                        'view' : 'settings.oauth'
                	},
                	{
                		'name' : this.$t('settings.webauthn'),
                        'view' : 'settings.webauthn'
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

        methods: {
            selectTab(viewName) {
                this.$router.push({ name: viewName })
            },
        }
    }

</script>