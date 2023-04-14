<template>
    <div :class="[$root.userPreferences.displayMode === 'grid' ? 'tfa-grid' : 'tfa-list']" class="column is-narrow">
        <div class="tfa-container">
            <transition name="slideCheckbox">
                <div class="tfa-cell tfa-checkbox" v-if="isEditMode">
                    <div class="field">
                        <input class="is-checkradio is-small" :class="$root.showDarkMode ? 'is-white':'is-info'" :id="'ckb_' + account.id" :value="account.id" type="checkbox" :name="'ckb_' + account.id" @change="select(account.id)">
                        <label tabindex="0" :for="'ckb_' + account.id" v-on:keypress.space.prevent="select(account.id)"></label>
                    </div>
                </div>
            </transition>
            <div tabindex="0" class="tfa-cell tfa-content is-size-3 is-size-4-mobile" @click="$emit('show', account)" @keyup.enter="$emit('show', account)" role="button">  
                <div class="tfa-text has-ellipsis">
                    <img :src="$root.appConfig.subdirectory + '/storage/icons/' + account.icon" v-if="account.icon && $root.userPreferences.showAccountsIcons" :alt="$t('twofaccounts.icon_for_account_x_at_service_y', {account: account.account, service: account.service})">
                    {{ displayService(account.service) }}<font-awesome-icon class="has-text-danger is-size-5 ml-2" v-if="$root.appSettings.useEncryption && account.account === $t('errors.indecipherable')" :icon="['fas', 'exclamation-circle']" />
                    <span class="is-family-primary is-size-6 is-size-7-mobile has-text-grey ">{{ account.account }}</span>
                </div>
            </div>
            <transition name="fadeInOut">
                <div class="tfa-cell tfa-edit has-text-grey" v-if="isEditMode">
                    <!-- <div class="tags has-addons"> -->
                        <router-link :to="{ name: 'editAccount', params: { twofaccountId: account.id }}" class="tag is-rounded mr-1" :class="$root.showDarkMode ? 'is-dark' : 'is-white'">
                        {{ $t('commons.edit') }}
                        </router-link>
                        <router-link :to="{ name: 'showQRcode', params: { twofaccountId: account.id }}" class="tag is-rounded" :class="$root.showDarkMode ? 'is-dark' : 'is-white'" :title="$t('twofaccounts.show_qrcode')">
                            <font-awesome-icon :icon="['fas', 'qrcode']" />
                        </router-link>
                    <!-- </div> -->
                </div>
            </transition>
            <transition name="fadeInOut">
                <div class="tfa-cell tfa-dots has-text-grey" v-if="isEditMode">
                    <font-awesome-icon :icon="['fas', 'bars']" />
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Twofaccount',

        data() {
            return {
            }
        },

        props: [
            'account',
            'isEditMode',
        ],

        methods: {

            /**
             * 
             */
            displayService(service) {
                return service ? service : this.$t('twofaccounts.no_service')
            },

            /**
             * 
             */
            select(accountId) {
                this.$emit('selected', accountId)
            },

            
        }
    }
</script>