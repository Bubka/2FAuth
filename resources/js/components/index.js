import Vue from 'vue'
import Button from './Button'
import FieldError from './FieldError'
import FormWrapper from './FormWrapper'
import VueFooter from './Footer'

// Components that are registered globaly.
[
	Button,
    FieldError,
    FormWrapper,
    VueFooter,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
