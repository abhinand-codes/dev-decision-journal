import { defineStore } from 'pinia';
import api from '../services/api';

export const useDecisionsStore = defineStore('decisions', {
    state: () => ({
        decisions: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            prev_page_url: null,
            next_page_url: null,
            total: 0
        },
        loading: false,
        error: null,
    }),

    actions: {
        async fetchDecisions(params = {}) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.get('/decisions', { params });

                // Laravel API Resource paginated format: { data: [], links: {}, meta: {} }
                this.decisions = response.data.data;

                if (response.data.meta) {
                    this.pagination = {
                        current_page: response.data.meta.current_page,
                        last_page: response.data.meta.last_page,
                        total: response.data.meta.total,
                        prev_page_url: response.data.links?.prev,
                        next_page_url: response.data.links?.next,
                    };
                }
            } catch (err) {
                this.error = err.response?.data?.message || 'Failed to fetch decisions. Please try again later.';
                if (err.response?.status === 403) {
                    this.error = 'You do not have permission to view these decisions.';
                }
            } finally {
                this.loading = false;
            }
        }
    }
});
