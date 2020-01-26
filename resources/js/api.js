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

    if ( error.response.status === 404 ) {

        router.push({name: '404', params: { err : error.response }})
    }
    else {

        // router.push({ name: 'genericError', params: { err: error.response } });
        return Promise.reject(error)
    }


})