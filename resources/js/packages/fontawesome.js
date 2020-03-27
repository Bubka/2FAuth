import Vue  from 'vue'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


import {
    faPlus,
    faQrcode,
    faImage,
    faTrash,
    faEdit,
    faCheck,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars
} from '@fortawesome/free-solid-svg-icons'

library.add(
    faPlus,
    faQrcode,
    faImage,
    faTrash,
    faEdit,
    faCheck,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars
);

Vue.component('font-awesome-icon', FontAwesomeIcon)