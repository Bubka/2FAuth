
axios.interceptors.request.use(request => {

    const token = localStorage.getItem('jwt')

    if(token) {
        request.headers.common['Authorization'] = 'Bearer ' + token
    }

    request.headers.common['Content-Type'] = 'application/json'

    return request
})