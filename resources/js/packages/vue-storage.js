import Vue          from 'vue'
import { Plugin } from 'vue2-storage'

// You can specify the plug-in configuration when connecting, passing the second object to Vue.use
Vue.use(Plugin, {
    prefix: '',
    driver: 'local',
    ttl: 60 * 60 * 24 * 1000 * 122, // 4 month
    replacer: (key, value) => value
})