import axios from "axios"
import { useUserStore } from '@/stores/user'
import { useErrorHandler } from '@2fauth/stores'

export const httpClientFactory = (endpoint = 'api') => {
	let baseURL
    const subdir = window.appConfig.subdirectory

	if (endpoint === 'web') {
		baseURL = subdir + '/'
	} else {
		baseURL = subdir + '/api/v1'
	}

	const httpClient = axios.create({
		baseURL,
		headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
		withCredentials: true,
	})

	// httpClient.interceptors.request.use(
    //     async function (config) {
    //         // We get a CSRF token when needed
    //         const cookies = Object.fromEntries(document.cookie.split('; ').map(c => c.split('=')))
    //         console.log(cookies)

    //         if (! Object.hasOwnProperty(cookies, 'XSRF-TOKEN') && ['post', 'put', 'patch', 'delete'].includes(config.method))
    //         {
    //             await axios.get('/refresh-csrf', {withCredentials:true})
    //             return config
    //         }

    //         return config
    //     },
    //     (error) => {
    //         Promise.reject(error)
    //     }
    // )

    httpClient.interceptors.response.use(
        (response) => {
            return response;
        },
        async function (error) {
            const originalRequestConfig = error.config

            // Here we handle a missing/invalid CSRF cookie
            // We try to get a fresh on, but only once.
            if (error.response.status === 419 && ! originalRequestConfig._retried) {
                originalRequestConfig._retried = true;
                await axios.get('/refresh-csrf')
                return httpClient.request(originalRequestConfig)
            }

            // api calls are stateless so when user inactivity is detected
            // by the backend middleware, it cannot logout the user directly
            // so it returns a 418 response.
            // We catch the 418 response and log the user out
            if (error.response.status === 418) {
                const user = useUserStore()
                user.logout({ kicked: true})
            }
            
            if (error.response && [407].includes(error.response.status)) {
                useErrorHandler().show(error)
                return new Promise(() => {})
            }

            // Return the error when we need to handle it at component level
            if (error.config.hasOwnProperty('returnError') && error.config.returnError === true) {
                return Promise.reject(error)
            }
            
            if (error.response && [401].includes(error.response.status)) {
                const user = useUserStore()
                user.tossOut()
            }

            // Always return the form validation errors
            if (error.response.status === 422) {
                return Promise.reject(error)
            }

            // Not found
            if (error.response.status === 404) {
                useErrorHandler().notFound()
                return new Promise(() => {})
            }

            useErrorHandler().show(error)
            return new Promise(() => {})
        }
    )

	return httpClient
}