import Vue              from 'vue'
import App              from './App'
import Button           from './Button'
import FieldError       from './FieldError'
import FormWrapper      from './FormWrapper'
import FormField        from './FormField'
import FormPasswordField        from './FormPasswordField'
import FormSelect       from './FormSelect'
import FormSwitch       from './FormSwitch'
import FormToggle       from './FormToggle'
import FormCheckbox     from './FormCheckbox'
import FormButtons      from './FormButtons'
import VueFooter        from './Footer'
import Kicker           from './Kicker'
import SettingTabs      from './SettingTabs'
import ResponsiveWidthWrapper from './ResponsiveWidthWrapper'

// Components that are registered globaly.
[
    App,
	Button,
    FieldError,
    FormWrapper,
    FormField,
    FormPasswordField,
    FormSelect,
    FormSwitch,
    FormToggle,
    FormCheckbox,
    FormButtons,
    VueFooter,
    Kicker,
    SettingTabs,
    ResponsiveWidthWrapper
].forEach(Component => {
	Vue.component(Component.name, Component)
})
