import { defineStore } from 'pinia';
import api from '@/lib/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        isAuthenticating: false
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        userName: (state) => state.user?.name || '',
        userInitial: (state) => state.user?.name?.charAt(0).toUpperCase() || '',
        isUserAdmin: (state) => state.user?.is_admin,
    },

    actions: {
        async getCsrf() {
            await api.get('/sanctum/csrf-cookie');
        },

        async login(email, password) {
            try {
                this.isAuthenticating = true;
                await this.getCsrf();
                const response = await api.post('/api/login', { email, password });

                this.user = response.data.user;

                return { success: true };
            } catch (error) {
                const response = error.response;
                return {
                    success: false,
                    message: response?.data?.message || 'Login failed.',
                    errors: response?.data?.errors || {},
                };
            } finally {
                this.isAuthenticating = false;
            }
        },

        async register(name, email, password, password_confirmation, is_admin=false) {
            try {
                this.isAuthenticating = true;
                await this.getCsrf();
                await api.post('/api/register', { name, email, password, password_confirmation, is_admin });

                // automatically login after register
                return await this.login(email, password);
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || 'Registration failed',
                };
            } finally {
                this.isAuthenticating = false;
            }
        },

        async fetchUser() {
            try {
                const response = await api.get('/api/me');
                this.user = response.data;
            } catch {
                this.user = null;
            }
        },

        async logout() {
            try {
                await this.getCsrf();
                await api.post('/api/logout');
            } catch (error) {
                console.error('Logout request failed:', error);
            } finally {
                this.user = null;

                // clear all cookies on the client
                document.cookie = 'laravel_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';

                window.location.href = '/auth';
            }
        },

        async hydrate() {
            if (this.isAuthenticating) return;
            this.isAuthenticating = true;

            try {
                await this.fetchUser();
            } catch {
                this.user = null;
            } finally {
                this.isAuthenticating = false;
            }
        }
    },
});
