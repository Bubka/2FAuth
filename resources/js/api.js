import Vue      from 'vue'
import axios    from 'axios'
import VueAxios from 'vue-axios'
import router   from './routes.js'

Vue.use(VueAxios, axios)

Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     Vue.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

Vue.axios.interceptors.request.use(function (request) {

    request.headers.common['Content-Type'] = 'application/json'
    return request
})

Vue.axios.interceptors.response.use(response => response, error => {

    // Return the error when we need to handle it at component level
    if( error.config.hasOwnProperty('returnError') && error.config.returnError === true ) {
        return Promise.reject(error);
    }

    // Return the error for form validation at component level
    if( error.response.status === 422 ) {
        return Promise.reject(error);
    }

    // Otherwise we push to a specific or generic error view
    let routeName = 'genericError'

    if ( error.response.status === 401 ) {
        routeName = 'login'
    }

    if ( error.response.status === 407 ) {
        router.push({ name: 'genericError', params: { err: error.response, closable: false } })
        throw new Vue.axios.Cancel();
    }

    // api calls are stateless so when user inactivity is detected
    // by the backend middleware it cannot logout the user directly
    // so it returns a 418 response.
    // We catch the 418 response and push the user to the login view
    // with the instruction to request a session logout
    if ( error.response.status === 418 ) {
        router.push({ name: 'login', params: { forceLogout: true } })
        throw new Vue.axios.Cancel();
    }
    
    if ( error.response.status === 404 ) routeName = '404'

    router.push({ name: routeName, params: { err: error.response } })
    throw new Vue.axios.Cancel();

})