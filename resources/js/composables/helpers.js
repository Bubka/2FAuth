
export function startsWithUppercase(str) {
    return str.substr(0, 1).match(/[A-Z\u00C0-\u00DC]/);
}

export function asArray(payload) {
	if (Array.isArray(payload))
		return payload

	if (Array.isArray(payload?.data))
		return payload.data

	return []
}