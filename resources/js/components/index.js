import Vue from 'vue'
import Button from './Button'
import FieldError from './FieldError'

// Components that are registered globaly.
[
	Button,
    FieldError,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
