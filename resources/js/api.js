import Vue      from 'vue'
import axios    from 'axios'
import VueAxios from 'vue-axios'
import router   from './routes.js'

Vue.use(VueAxios, axios)

Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Vue.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


Vue.axios.interceptors.request.use(function (request) {

    const authToken = localStorage.getItem('jwt')

    if(authToken) {
        request.headers.common['Authorization'] = 'Bearer ' + authToken
    }

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
        localStorage.removeItem('jwt');
        localStorage.removeItem('user');
        routeName = 'login'
    }
    
    if ( error.response.status === 404 ) routeName = '404'

    router.push({ name: routeName, params: { err: error.response } })
    throw new Vue.axios.Cancel();

})