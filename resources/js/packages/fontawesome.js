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
    faCheckSquare,
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
    faUpload,
    faGlobe,
    faBook,
    faFlask,
    faCode,
    faCopy,
    faSortAlphaDown,
    faSortAlphaUp,
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
    faCheckSquare,
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
    faUpload,
    faGlobe,
    faBook,
    faFlask,
    faCode,
    faCopy,
    faSortAlphaDown,
    faSortAlphaUp,
);

Vue.component('font-awesome-icon', FontAwesomeIcon)