import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('auth_token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await api.post('/login', credentials);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('auth_token', this.token);
                return response;
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                await api.post('/logout');
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('auth_token');
            }
        },

        async register(data) {
            try {
                const response = await api.post('/register', data);
                this.token = response.data.token;
                this.user = response.data.user;
                localStorage.setItem('auth_token', this.token);
                return response;
            } catch (error) {
                throw error;
            }
        }
    }
});
