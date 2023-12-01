
export default class Errors {
  /**
   * Create a new error bag instance.
   */
  constructor () {
    this.errors = {}
  }

  /**
   * Set the errors object or field error messages.
   *
   * @param {Object|String} field
   * @param {Array|String|undefined} messages
   */
  set (field, messages) {
    if (typeof field === 'object') {
      this.errors = field
    } else {
      this.set({ ...this.errors, [field]: arrayWrap(messages) })
    }
  }

  /**
   * Get all the errors.
   *
   * @return {Object}
   */
  all () {
    return this.errors
  }

  /**
   * Determine if there is an error for the given field.
   *
   * @param  {String} field
   * @return {Boolean}
   */
  has (field) {
    return this.errors.hasOwnProperty(field)
  }

  /**
   * Determine if there are any errors for the given fields.
   *
   * @param  {...String} fields
   * @return {Boolean}
   */
  hasAny (...fields) {
    return fields.some(field => this.has(field))
  }

  /**
   * Determine if there are any errors.
   *
   * @return {Boolean}
   */
  any () {
    return Object.keys(this.errors).length > 0
  }

  /**
   * Get the first error message for the given field.
   *
   * @param  String} field
   * @return {String|undefined}
   */
  get (field) {
    if (this.has(field)) {
      return this.getAll(field)[0]
    }
  }

  /**
   * Get all the error messages for the given field.
   *
   * @param  {String} field
   * @return {Array}
   */
  getAll (field) {
    return arrayWrap(this.errors[field] || [])
  }

  /**
   * Get the error message for the given fields.
   *
   * @param  {...String} fields
   * @return {Array}
   */
  only (...fields) {
    const messages = []

    fields.forEach(field => {
      const message = this.get(field)

      if (message) {
        messages.push(message)
      }
    })

    return messages
  }

  /**
   * Get all the errors in a flat array.
   *
   * @return {Array}
   */
  flatten () {
    return Object.values(this.errors).reduce((a, b) => a.concat(b), [])
  }

  /**
   * Clear one or all error fields.
   *
   * @param {String|undefined} field
   */
  clear (field) {
    const errors = {}

    if (field) {
      Object.keys(this.errors).forEach(key => {
        if (key !== field) {
          errors[key] = this.errors[key]
        }
      })
    }

    this.set(errors)
  }
}

/**
 * If the given value is not an array, wrap it in one.
 *
 * @param  {Any} value
 * @return {Array}
 */
function arrayWrap (value) {
  return Array.isArray(value) ? value : [value]
}