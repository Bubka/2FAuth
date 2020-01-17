import Vue from 'vue'
import Button from './Button'
import { FormError, FormErrors, FieldError } from './form'

// Components that are registered globaly.
[
	Button,
    FormError,
    FormErrors,
    FieldError
].forEach(Component => {
	Vue.component(Component.name, Component)
})
