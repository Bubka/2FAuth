import axios from "axios"
import { useUserStore } from '@/stores/user'
import { useNotifyStore } from '@/stores/notify'

export const httpClientFactory = (endpoint = 'api') => {
	let baseURL

	if (endpoint === 'web') {
		baseURL = '/'
	} else {
		baseURL = '/api/v1'
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

            // Return the error when we need to handle it at component level
            if (error.config.hasOwnProperty('returnError') && error.config.returnError === true) {
                return Promise.reject(error)
            }
            
            if (error.response && [401].includes(error.response.status)) {
                const user = useUserStore()
                user.reset()
            }

            // Always return the form validation errors
            if (error.response.status === 422) {
                return Promise.reject(error)
            }

            useNotifyStore().error(error)
            return new Promise(() => {})
        }
    )

	return httpClient
}