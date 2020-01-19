import Vue from 'vue'
import Button from './Button'

// Components that are registered globaly.
[
	Button,
].forEach(Component => {
	Vue.component(Component.name, Component)
})
