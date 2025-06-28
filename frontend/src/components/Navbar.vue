<template>
    <nav class="sticky top-0 left-0 w-full bg-white shadow-sm z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-2">
                <RouterLink to="/" class="flex items-center space-x-3">
                    <img
                        src="/logo.svg"
                        alt="Logo"
                        class="h-10 w-10 rounded-full"
                    />
                    <span class="text-xl font-bold text-accent">TaskManager</span>
                </RouterLink>

                <div v-if="isAuthenticated" class="hidden md:flex items-center space-x-6">
                    <RouterLink
                        v-for="link in validNavLinks"
                        :key="link.name"
                        :to="link.path"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors"
                        :class="isActive(link.path)
                            ? 'bg-accent/10 text-accent font-semibold backdrop-blur-sm'
                            : 'text-text-light hover:text-text hover:bg-border hover:backdrop-blur-sm transition'"
                    >
                        {{ link.name }}
                    </RouterLink>
                    <div v-click-outside="closeUserMenu" class="relative">
                        <button
                            class="flex items-center space-x-2 text-sm focus:outline-none"
                            @click="toggleUserMenu"
                        >
                            <div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-semibold">
                                {{ userInitial }}
                            </div>
                            <span class="hidden md:block font-medium text-text">{{ userName }}</span>
                            <Icon name="ChevronDown" custom-class="h-4 w-4 text-gray-400" />
                        </button>

                        <transition name="fade">
                            <div
                                v-if="showUserMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-border ring-opacity-5 py-1 z-10"
                            >
                                <p class="flex flex-row items-center px-4 py-2 text-sm text-text hover:bg-border cursor-pointer">
                                    <Icon name="UserCircle" custom-class="w-5 h-5 text-text-light mr-2" />
                                    Your Profile
                                </p>
                                <p class="flex flex-row items-center px-4 py-2 text-sm text-text hover:bg-border cursor-pointer">
                                    <Icon name="Cog6Tooth" custom-class="w-5 h-5 text-text-light mr-2" />
                                    Settings
                                </p>
                                <button
                                    class="flex flex-row items-center w-full text-left px-4 py-2 text-sm text-text hover:bg-border"
                                    @click="logout"
                                >
                                    <Icon name="ArrowLeftStartOnRectangle" custom-class="w-5 h-5 mr-2" />
                                    Sign out
                                </button>
                            </div>
                        </transition>
                    </div>
                </div>
                <button class="md:hidden ml-2" @click="toggleMobileMenu">
                    <Icon name="Bars3" custom-class="h-6 w-6 text-gray-500" />
                </button>
            </div>
        </div>

        <transition name="fade">
            <div v-if="showMobileMenu && isAuthenticated" class="md:hidden px-4 pb-4">
                <div class="space-y-2 mt-2">
                    <RouterLink
                        v-for="link in navLinks"
                        :key="link.name"
                        :to="link.path"
                        class="block w-full text-left px-3 py-2 text-base font-medium rounded-md transition-colors"
                        :class="isActive(link.path) ? 'bg-primary-100 text-primary-700' : 'text-text-light hover:text-text'"
                        @click="toggleMobileMenu"
                    >
                        {{ link.name }}
                    </RouterLink>
                    <button class="w-full text-left px-3 py-2 text-base font-medium text-text-light hover:text-text">
                        Sign out
                    </button>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { useAuthStore } from '@/store/authStore';
    import Icon from './Icon.vue';

    const authStore = useAuthStore();
    const route = useRoute();
    const router = useRouter();

    const navLinks = [
        { name: 'My Tasks', path: '/dashboard' },
        { name: 'Statistics', path: '/statistics' },
        { name: 'Admin', path: '/admin' }
    ];

    const showUserMenu = ref(false);
    const showMobileMenu = ref(false);
    const isActive = (path) => route.path.startsWith(path);

    const userInitial = computed(() => authStore.userInitial);
    const isAuthenticated = computed(() => authStore.isAuthenticated);
    const userName = computed(() => authStore.userName);
    const validNavLinks = computed(() => {
        const { isUserAdmin } = authStore;

        // Hide admin tab for non admin users
        return isUserAdmin
            ? navLinks
            : navLinks.filter(link => link.name !== 'Admin');
    });

    const toggleUserMenu = () => {
        showUserMenu.value = !showUserMenu.value;
    };

    const toggleMobileMenu = () => {
        showMobileMenu.value = !showMobileMenu.value;
    };

    const closeUserMenu = () => {
        showUserMenu.value = false;
    };

    const logout = () => {
        authStore.logout();
        showUserMenu.value = false;
        router.push('/auth');
    };
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.2s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
</style>
