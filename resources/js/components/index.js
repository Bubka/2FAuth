import Vue from 'vue'
import Button from './Button'
import FieldError from './FieldError'
import FormWrapper from './FormWrapper'
import FormField from './FormField'
import FormButtons from './FormButtons'
import VueFooter from './Footer'

// Components that are registered globaly.
[
	Button,
    FieldError,
    FormWrapper,
    FormField,
    FormButtons,
    VueFooter,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
