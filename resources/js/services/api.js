import axios from 'axios';
import router from '../router';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

// Request interceptor for auth token
api.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Response interceptor for auth failure
api.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem('auth_token');
        router.push('/login');
    }
    return Promise.reject(error);
});

export default api;
