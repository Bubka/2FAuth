<template>
	<div>
		<div class="tabs is-toggle is-toggle-rounded is-small">
		    <ul>
                <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
                    <a @click="selectTab(tab)">{{ tab.name }}</a>
                </li>
		    </ul>
		</div>
		<div>
			<settings v-if="activeTab === 'settings'"></settings>
			<account v-if="activeTab === 'account'"></account>
			<password v-if="activeTab === 'password'"></password>
		</div>
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
                		'name' : 'settings',
                		'isActive': true
                	},
                	{
                		'name' : 'account',
                		'isActive': false
                	},
                	{
                		'name' : 'password',
                		'isActive': false
                	},
            	],
            	activeTab: 'settings'
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