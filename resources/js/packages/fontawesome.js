import Vue  from 'vue'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import {
    faPlus,
    faPlusCircle,
    faQrcode,
    faImage,
    faTrash,
    faCheck,
    faTimes,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars,
    faSpinner,
    faCaretDown,
    faLayerGroup,
    faMinusCircle,
    faExclamationCircle,
    faPenSquare,
    faTh,
    faList,
    faTimesCircle,
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
    faCheck,
    faTimes,
    faLock,
    faLockOpen,
    faSearch,
    faEllipsisH,
    faBars,
    faSpinner,
    faGithubAlt,
    faCaretDown,
    faLayerGroup,
    faMinusCircle,
    faExclamationCircle,
    faPenSquare,
    faTh,
    faList,
    faTimesCircle,
);

Vue.component('font-awesome-icon', FontAwesomeIcon)