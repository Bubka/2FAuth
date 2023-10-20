import { defineStore } from 'pinia'
import router from '@/router'
const { notify }  = useNotification()

export const useNotifyStore = defineStore({
    id: 'notify',

    state: () => {
        return {
            err: null,
            message: null,
            originalMessage: null,
            debug: null,
        }
    },

    getters: {
    },

    actions: {
        parseError(err) {
            this.$reset
            this.err = err

            // Hnalde axios response error
            if (err.response) {
                if (err.response.status === 407) {
                    this.message = trans('errors.auth_proxy_failed'),
                    this.originalMessage = trans('errors.auth_proxy_failed_legend')
                }
                else if (err.response.status === 403) {
                    this.message = trans('errors.unauthorized'),
                    this.originalMessage = trans('errors.unauthorized_legend')
                }
                else if(err.response.data) {
                    this.message = err.response.data.message,
                    this.originalMessage = err.response.data.originalMessage ?? null
                    this.debug = err.response.data.debug ?? null
                }
            } else {
                this.message = err.message
                this.debug = err.stack ?? null
            }
            // else if (err.request) {
                
            // 
        },

        error(err) {
            this.parseError(err)
            router.push({ name: 'genericError' })
        },

        info(notification) {
            notify({ type: 'is-success', ...notification})
        },

        warn(notification) {
            notify({ type: 'is-warning', ...notification})
        },

        alert(notification) {
            notify({ type: 'is-danger', ...notification})
        },

        action(notification) {
            notify({ type: 'is-dark', ...notification})
        },

        clear() {
            notify({ clean: true })
        }

    },
})
