import Vue              from 'vue'
import App              from './App'
import Button           from './Button'
import FieldError       from './FieldError'
import FormWrapper      from './FormWrapper'
import FormField        from './FormField'
import FormSelect       from './FormSelect'
import FormSwitch       from './FormSwitch'
import FormToggle       from './FormToggle'
import FormCheckbox     from './FormCheckbox'
import FormButtons      from './FormButtons'
import VueFooter        from './Footer'
import Kicker           from './Kicker'

// Components that are registered globaly.
[
    App,
	Button,
    FieldError,
    FormWrapper,
    FormField,
    FormSelect,
    FormSwitch,
    FormToggle,
    FormCheckbox,
    FormButtons,
    VueFooter,
    Kicker
].forEach(Component => {
	Vue.component(Component.name, Component)
})
