// import { ref } from 'vue'

export function useIdGenerator(fieldType, fieldName) {
	let prefix
	fieldName = fieldName.toString()

	switch (fieldType) {
		case 'text':
			prefix = 'txt'
			break
		case 'button':
			prefix = 'btn'
			break
		case 'email':
			prefix = 'eml'
			break
		case 'password':
			prefix = 'pwd'
			break
		case 'radio':
			prefix = 'rdo'
			break
		case 'label':
			prefix = 'lbl'
			break
		default:
			prefix = 'txt'
			break
	}

	return {
		inputId: prefix + fieldName[0].toUpperCase() + fieldName.toLowerCase().slice(1)
	}
}