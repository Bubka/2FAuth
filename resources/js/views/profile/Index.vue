<template>
    <div>
        <div class="columns is-centered">
            <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
        		<div class="tabs is-centered is-fullwidth">
        		    <ul>
                        <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
                            <a @click="selectTab(tab)">{{ tab.name }}</a>
                        </li>
        		    </ul>
        		</div>
            </div>
    	</div>
        <settings v-if="activeTab === $t('settings.settings')"></settings>
        <account v-if="activeTab === $t('settings.account')"></account>
        <password v-if="activeTab === $t('settings.password')"></password>
        <vue-footer :showButtons="true">
            <!-- Cancel button -->
            <p class="control">
                <router-link :to="{ name: 'accounts' }" class="button is-dark is-rounded">{{ $t('commons.close') }}</router-link>
            </p>
        </vue-footer>
    </div>
</template>

<script>

    import Settings from './Settings'
    import Account 	from './Account'
    import Password from './Password'

    export default {
        data(){
            return {
                tabs: [
                	{
                		'name' : this.$t('settings.settings'),
                		'isActive': true
                	},
                	{
                		'name' : this.$t('settings.account'),
                		'isActive': false
                	},
                	{
                		'name' : this.$t('settings.password'),
                		'isActive': false
                	},
            	],
            	activeTab: this.$t('settings.settings')
            }
        },

        components: {
        	Settings,
            Account,
            Password
        },

        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name == selectedTab.name);
                    if( tab.name == selectedTab.name ) {
                    	this.activeTab = selectedTab.name
                    }
                });
            }
        }
    };

</script>