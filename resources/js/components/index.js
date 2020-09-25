import Vue from 'vue'
import Button from './Button'
import FieldError from './FieldError'
import FormWrapper from './FormWrapper'
import FormField from './FormField'
import FormSelect from './FormSelect'
import FormSwitch from './FormSwitch'
import FormButtons from './FormButtons'
import VueFooter from './Footer'

// Components that are registered globaly.
[
	Button,
    FieldError,
    FormWrapper,
    FormField,
    FormSelect,
    FormSwitch,
    FormButtons,
    VueFooter,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
