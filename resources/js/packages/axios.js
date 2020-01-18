import axios  from 'axios'
import router from '../routes'


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// window.axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let csrfToken = document.head.querySelector('meta[name="csrf-token"]');

if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.interceptors.request.use(request => {

    const token = localStorage.getItem('jwt')

    if(token) {
        request.headers.common['Authorization'] = 'Bearer ' + token
    }

    request.headers.common['Content-Type'] = 'application/json'

    return request
})

// Response interceptor
axios.interceptors.response.use(response => response, error => {

    const { status } = error.response

    if (status >= 500) {
        router.push({name: 'genericError', params: { err : error.response }})
    }

    if (status === 404) {
        router.push({name: '404'})
    }

    return Promise.reject(error)
})