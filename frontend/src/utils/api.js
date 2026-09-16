const api_url = "http://api.publicarlibros/api";

export const api = {
    request: async (method, endpoint, body) => {
        const options = { method, headers: { 'Content-Type': 'application/json' } };
        if (body !== undefined) options.body = JSON.stringify(body);
        const response = await fetch(`${api_url}${endpoint}`.replace(/([^:]\/)\//g, '$1'), options);
        if (!response.ok) {
            const error = await response.json().catch(() => ({}));
            throw new Error(error.message || `Error HTTP ${response.status}`);
        }
        return response.status === 204 ? null : response.json();
    },
    get: (endpoint) => api.request('GET', endpoint),
    post: (endpoint, body) => api.request('POST', endpoint, body),
    put: (endpoint, body) => api.request('PUT', endpoint, body),
    delete: (endpoint) => api.request('DELETE', endpoint),
};