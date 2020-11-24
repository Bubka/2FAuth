<template>
    <div>
        <div class="options-header has-background-black-ter">
            <div class="columns is-centered">
                <div class="form-column column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
            		<div class="tabs is-centered is-fullwidth">
            		    <ul>
                            <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
                                <a @click="selectTab(tab)">{{ tab.name }}</a>
                            </li>
            		    </ul>
            		</div>
                </div>
        	</div>
        </div>
        <div class="options-tabs">
            <options v-if="activeTab === $t('settings.options')"></options>
            <account v-if="activeTab === $t('settings.account')"></account>
            <password v-if="activeTab === $t('settings.password')"></password>
        </div>
        <vue-footer :showButtons="true">
            <!-- Cancel button -->
            <p class="control">
                <a class="button is-dark is-rounded" @click.stop="exitSettings">
                    {{ $t('commons.close') }}
                </a>
            </p>
        </vue-footer>
    </div>
</template>

<script>

    import Options from './Options'
    import Account 	from './Account'
    import Password from './Password'

    export default {
        data(){
            return {
                tabs: [
                	{
                		'name' : this.$t('settings.options'),
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
            	activeTab: this.$t('settings.options')
            }
        },

        components: {
        	Options,
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
            },

            exitSettings: function(event) {
                if (event) {
                    this.$notify({ clean: true })
                    this.$router.push({ name: 'accounts' })
                }
            }
        }
    };

</script>