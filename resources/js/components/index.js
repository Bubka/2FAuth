import Vue              from 'vue'
import App              from './App'
import Button           from './Button'
import FieldError       from './FieldError'
import FormWrapper      from './FormWrapper'
import FormField        from './FormField'
import FormSelect       from './FormSelect'
import FormSwitch       from './FormSwitch'
import FormCheckbox     from './FormCheckbox'
import FormButtons      from './FormButtons'
import VueFooter        from './Footer'
import Kicker           from './Kicker'
import Breadcrumb       from './Breadcrumb'

// Components that are registered globaly.
[
    App,
	Button,
    FieldError,
    FormWrapper,
    FormField,
    FormSelect,
    FormSwitch,
    FormCheckbox,
    FormButtons,
    VueFooter,
    Kicker,
    Breadcrumb
].forEach(Component => {
	Vue.component(Component.name, Component)
})
