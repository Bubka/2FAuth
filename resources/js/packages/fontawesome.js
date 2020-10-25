import Vue  from 'vue'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
    faPlus,
    faPlusCircle,
    faQrcode,
    faImage,
    faTrash,
    faEdit,
    faCheck,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars,
    faSpinner,
    faChevronLeft,
    faCaretUp,
    faCaretDown,
    faLayerGroup,
    faMinusCircle,
} from '@fortawesome/free-solid-svg-icons'

import {
    faGithubAlt
} from '@fortawesome/free-brands-svg-icons'

library.add(
    faPlus,
    faPlusCircle,
    faQrcode,
    faImage,
    faTrash,
    faEdit,
    faCheck,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars,
    faSpinner,
    faGithubAlt,
    faChevronLeft,
    faCaretUp,
    faCaretDown,
    faLayerGroup,
    faMinusCircle,
);

Vue.component('font-awesome-icon', FontAwesomeIcon)