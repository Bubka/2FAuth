import Vue from 'vue'
import Button from './Button'
import FieldError from './FieldError'
import FormWrapper from './FormWrapper'

// Components that are registered globaly.
[
	Button,
    FieldError,
    FormWrapper,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
