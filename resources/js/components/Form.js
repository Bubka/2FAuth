import Vue      from 'vue'
import Errors   from './FormErrors'

// import { deepCopy } from './util'

class Form {
    /**
     * Create a new form instance.
     *
     * @param {Object} data
     */
    constructor (data = {}) {
        this.isBusy = false
        this.isDisabled = false
        // this.successful = false
        this.errors = new Errors()
        // this.originalData = deepCopy(data)

        Object.assign(this, data)
    }

    /**
     * Fill form data.
     *
     * @param {Object} data
     */
    fill (data) {
        this.keys().forEach(key => {
            this[key] = data[key]
        })
    }

    /**
     * Get the form data.
     *
     * @return {Object}
     */
    data () {
        return this.keys().reduce((data, key) => (
            { ...data, [key]: this[key] }
        ), {})
    }

    /**
     * Get the form data keys.
     *
     * @return {Array}
     */
    keys () {
        return Object.keys(this)
            .filter(key => !Form.ignore.includes(key))
    }

    /**
     * Start processing the form.
     */
    startProcessing () {
        this.errors.clear()
        this.isBusy = true
        // this.successful = false
    }

    /**
     * Finish processing the form.
     */
    finishProcessing () {
        this.isBusy = false
        // this.successful = true
    }

    /**
     * Clear the form errors.
     */
    clear () {
        this.errors.clear()
        // this.successful = false
    }

    /**
     * Reset the form fields.
     */
    reset () {
      Object.keys(this)
        .filter(key => !Form.ignore.includes(key))
        .forEach(key => {
          this[key] = deepCopy(this.originalData[key])
        })
    }

    /**
     * Submit the form via a GET request.
     *
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    get (url, config = {}) {
        return this.submit('get', url, config)
    }

    /**
     * Submit the form via a POST request.
     *
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    post (url, config = {}) {
        return this.submit('post', url, config)
    }

    /**
     * Submit the form via a PATCH request.
     *
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    patch (url, config = {}) {
        return this.submit('patch', url, config)
    }

    /**
     * Submit the form via a PUT request.
     *
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    put (url, config = {}) {
        return this.submit('put', url, config)
    }

    /**
     * Submit the form via a DELETE request.
     *
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    delete (url, config = {}) {
        return this.submit('delete', url, config)
    }

    /**
     * Submit the form data via an HTTP request.
     *
     * @param  {String} method (get, post, patch, put)
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    submit (method, url, config = {}) {
        this.startProcessing()

        const data = method === 'get'
            ? { params: this.data() }
            : this.data()

        return new Promise((resolve, reject) => {
            // (Form.axios || axios).request({ url: this.route(url), method, data, ...config })
            Vue.axios.request({ url: this.route(url), method, data, ...config })
                .then(response => {
                    this.finishProcessing()

                    resolve(response)
                })
                .catch(error => {
                    this.isBusy = false

                    if (error.response) {
                        this.errors.set(this.extractErrors(error.response))
                    }

                    reject(error)
                })
        })
    }

    /**
     * Submit the form data via an HTTP request.
     *
     * @param  {String} method (get, post, patch, put)
     * @param  {String} url
     * @param  {Object} config (axios config)
     * @return {Promise}
     */
    upload (url, formData, config = {}) {
        this.startProcessing()

        return new Promise((resolve, reject) => {
            // (Form.axios || axios).request({ url: this.route(url), method, data, ...config })
            Vue.axios.request({ url: this.route(url), method: 'post', data: formData, header: {'Content-Type' : 'multipart/form-data'} })
                .then(response => {
                    this.finishProcessing()

                    resolve(response)
                })
                .catch(error => {
                    this.isBusy = false

                    if (error.response) {
                        this.errors.set(this.extractErrors(error.response))
                    }

                    reject(error)
                })
        })
    }

    /**
     * Extract the errors from the response object.
     *
     * @param  {Object} response
     * @return {Object}
     */
    extractErrors (response) {
        if (!response.data || typeof response.data !== 'object') {
            return { error: Form.errorMessage }
        }

        if (response.data.errors) {
            return { ...response.data.errors }
        }

        if (response.data.message) {
            return { error: response.data.message }
        }

        return { ...response.data }
    }

    /**
     * Get a named route.
     *
     * @param  {String} name
     * @return {Object} parameters
     * @return {String}
     */
    route (name, parameters = {}) {
        let url = name

        if (Form.routes.hasOwnProperty(name)) {
            url = decodeURI(Form.routes[name])
        }

        if (typeof parameters !== 'object') {
            parameters = { id: parameters }
        }

        Object.keys(parameters).forEach(key => {
            url = url.replace(`{${key}}`, parameters[key])
        })

        return url
    }

    /**
     * Clear errors on keydown.
     *
     * @param {KeyboardEvent} event
     */
    onKeydown (event) {
        if (event.target.name) {
            this.errors.clear(event.target.name)
        }
    }
}

Form.routes = {}
Form.errorMessage = 'Something went wrong. Please try again.'
Form.ignore = ['isBusy', 'isDisabled', 'errors', 'originalData']

export default Form
