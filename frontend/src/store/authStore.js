import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: null,
        // temporary fake users
        fakeUsers: [
            {
                id: 1,
                name: 'Chris Chan',
                email: 'chris@test.com',
                password: 'password',
                role: 'admin',
                active: true,
                tasks: [1, 2]
            },
            {
                id: 2,
                name: 'Mark',
                email: 'mark@test.com',
                password: 'password',
                role: 'user',
                active: true,
                tasks: [3]
            },
        ],
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        userName: (state) => state.user?.name || '',
        userInitial: (state) => state.user?.name?.charAt(0).toUpperCase() || '',
    },

    actions: {
        login(email, password) {
            const foundUser = this.fakeUsers.find(
                (u) => u.email === email && u.password === password
            );

            if (foundUser) {
                this.user = foundUser;
                this.token = 'fake-token';
                localStorage.setItem('authUser', JSON.stringify(foundUser));
                localStorage.setItem('authToken', this.token);
                return { success: true };
            } else {
                return { success: false, message: 'Invalid email or password' };
            }
        },

        register(name, email, password) {
            const exists = this.fakeUsers.find(u => u.email === email);

            if (exists) {
                return { success: false, message: 'Email already registered' };
            }

            const newUser = {
                id: this.fakeUsers.length + 1,
                name,
                email,
                password,
                role: 'user'
            };

            this.fakeUsers.push(newUser);
            this.user = newUser;
            this.token = 'fake-token';
            localStorage.setItem('authUser', JSON.stringify(newUser));
            localStorage.setItem('authToken', this.token);
            return { success: true };
        },

        logout() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('authUser');
            localStorage.removeItem('authToken');
        },

        hydrate() {
            const savedUser = localStorage.getItem('authUser');
            const savedToken = localStorage.getItem('authToken');
            if (savedUser && savedToken) {
                this.user = JSON.parse(savedUser);
                this.token = savedToken;
            }
        },
    }
});
