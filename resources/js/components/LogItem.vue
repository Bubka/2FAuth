<script setup>
    import { UseColorMode } from '@vueuse/components'
    import {
        LucideSmartphone,
        LucideMonitor,
        LucideTablet,
        LucideCheck,
        LucideX
    } from '@lucide/vue'

    const $2fauth = inject('2fauth')

    const props = defineProps({
        log: {
            type: Object,
            required: true
        },
        logType: {
            type: String,
            required: true,
            validator(value, props) {
                return ['auth', 'otp'].includes(value)
            }
        }
    })

    const isSuccessfulLogin = (log) => {
        return log.login_successful && log.login_at
    }

    const isSuccessfulLogout = (log) => {
        return !log.login_at && log.logout_at
    }

    const isFailedEntry = (log) => {
        return !log.login_successful && !log.logout_at
    }
</script>

<template>
    <div class="list-item is-size-6 is-size-7-mobile has-text-grey is-flex is-justify-content-space-between">
        <UseColorMode v-slot="{ mode }">
            <template v-if="props.logType == 'auth'">
                <div>
                    <div>
                        <i18n-t v-if="isFailedEntry(log)" keypath="message.failed_login_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ log.login_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else-if="isSuccessfulLogout(log)" keypath="message.successful_logout_on" tag="span">
                            <template v-slot:logout_at>
                                <span class="light-or-darker">{{ log.logout_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else-if="$2fauth.config.proxyAuth" keypath="message.viewed_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ log.login_at }}</span>
                            </template>
                        </i18n-t>
                        <i18n-t v-else keypath="message.successful_login_on" tag="span">
                            <template v-slot:login_at>
                                <span class="light-or-darker">{{ log.login_at }}</span>
                            </template>
                        </i18n-t>
                    </div>
                    <div>
                        {{ $t('label.IP') }}: <span class="light-or-darker">{{ log.ip_address }}</span> -
                        {{ $t('label.browser') }}: <span class="light-or-darker">{{ log.browser }}</span> -
                        {{ $t('label.operating_system_short') }}: <span class="light-or-darker">{{ log.platform }}</span>
                    </div>
                </div>
                <div :class="mode == 'dark' ? 'has-text-grey-darker' : 'has-text-grey-lighter'"
                    class="is-align-self-center ">
                    <div class="log-icon-wrapper">
                        <LucideSmartphone v-if="log.device=='phone'" class="log-icon-bg" />
                        <LucideTablet v-else-if="log.device=='tablet'" class="log-icon-bg" />
                        <LucideMonitor v-else class="log-icon-bg" />
                        <LucideX v-if="isFailedEntry(log)" stroke-width=3 class="log-icon-mark" :class="'has-text-danger'" />
                        <LucideCheck v-else stroke-width=3 class="log-icon-mark" :class="'has-text-success-50'" />
                    </div>
                </div>
            </template>
            <div v-else-if="props.logType == 'otp'">
                <i18n-t keypath="message.otp_generated_by_on" tag="span">
                    <template v-slot:otp>
                        <span class="light-or-darker">{{ log.otp_type.toUpperCase() }}</span>
                    </template>
                    <template v-slot:requester>
                        <span class="light-or-darker">{{ log.requester_name }} ({{ log.requester_email }})</span>
                    </template>
                    <template v-slot:generated_at>
                        <span class="light-or-darker">{{ new Date(log.generated_at).toLocaleString() }}</span>
                    </template>
                </i18n-t> -
                {{ $t('label.IP') }}: <span class="light-or-darker">{{ log.ip_address }}</span>
                <template v-if="log.otp_type == 'hotp'">
                    {{ ' - ' + $t('label.counter') }}: <span class="light-or-darker">{{ log.counter }}</span>
                </template>
            </div>
        </UseColorMode>
    </div>
</template>
