// Original component by Cretu Eusebiu
// https://github.com/cretueusebiu/vform

/**
 * Deep copy the given object.
 *
 * @param  {Object} obj
 * @return {Object}
 */
export function deepCopy (obj) {
	if (obj === null || typeof obj !== 'object') {
		return obj
	}

	const copy = Array.isArray(obj) ? [] : {}

	Object.keys(obj).forEach(key => {
		copy[key] = deepCopy(obj[key])
	})

	return copy
}

/**
 * If the given value is not an array, wrap it in one.
 *
 * @param  {Any} value
 * @return {Array}
 */
export function arrayWrap (value) {
	return Array.isArray(value) ? value : [value]
}