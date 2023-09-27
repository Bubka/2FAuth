import axios from "axios"

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

    // see https://github.com/fsgreco/vue3-laravel-api/blob/main/src/api/middlewareCSRF.js
	//httpClient.interceptors.request.use( middlewareCSRF, err => Promise.reject(err)) 

    httpClient.interceptors.response.use(
        (response) => {
            return response;
        },
        function (error) {
            if (
                error.response &&
                [401, 419].includes(error.response.status)
                // && store.getters["auth/authUser"] &&
                // !store.getters["auth/guest"]
            ) {
                // store.dispatch("auth/logout");
            }
            return Promise.reject(error);
        }
    )

	return httpClient
}