import Vue from 'vue'
import router from '../routes/routes'

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