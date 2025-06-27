<template>
    <div class="min-h-screen bg-bg flex flex-col">
        <div class="flex justify-center px-4 pt-28 pb-6 overflow-y-auto">
            <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
                <div class="relative mb-6">
                    <div class="flex justify-center items-center gap-8 relative">
                        <button
                            class="px-6 py-2 text-sm font-semibold transition-colors relative"
                            :class="authMode === 'login' ? 'text-accent' : 'text-gray-400 hover:text-gray-600'"
                            @click="authMode = 'login'"
                        >
                            Login
                        </button>
                        <button
                            class="px-6 py-2 text-sm font-semibold transition-colors relative"
                            :class="authMode === 'register' ? 'text-accent' : 'text-gray-400 hover:text-gray-600'"
                            @click="authMode = 'register'"
                        >
                            Register
                        </button>
                        <div
                            class="absolute bottom-0 h-0.5 bg-accent transition-all duration-300"
                            :style="{
                                width: authMode == 'login' ? '38px' : '58px',
                                left: authMode === 'login' ? 'calc(50% - 86px)' : 'calc(50% + 32px)'
                            }"
                        ></div>
                    </div>
                </div>

                <Transition name="fade" mode="out-in">
                    <form
                        v-if="authMode === 'login'"
                        key="login"
                        class="space-y-4"
                        @submit.prevent="login"
                    >
                        <p v-if="loginErrors.general" class="bg-danger-light text-danger text-sm rounded p-2 flex justify-center items-center gap-2">
                            <Icon name="ExclamationCircle" class="h-4 w-4" />
                            {{ loginErrors.general }}
                        </p>

                        <InputField
                            v-model="loginForm.email"
                            label="Email"
                            icon="Envelope"
                            type="email"
                            placeholder="Enter email"
                            required
                        />
                        <p v-if="loginErrors.email" class="text-danger text-xs pl-1 mt-1">
                            {{ loginErrors.email }}
                        </p>

                        <InputField
                            v-model="loginForm.password"
                            label="Password"
                            icon="LockClosed"
                            placeholder="Enter password"
                            :type="showLoginPassword ? 'text' : 'password'"
                            :show-toggle="Boolean(loginForm.password)"
                            required
                            @toggle="showLoginPassword = !showLoginPassword"
                        />
                        <p v-if="loginErrors.password" class="text-danger text-xs pl-1 mt-1">
                            {{ loginErrors.password }}
                        </p>
                        <button class="btn-primary w-full flex items-center justify-center" :disabled="isNavigating">
                            <span v-if="!isNavigating">Sign In</span>
                            <svg
                                v-else
                                class="animate-spin h-5 w-5 text-white"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    fill="none"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"
                                />
                            </svg>
                        </button>
                    </form>

                    <form
                        v-else
                        key="register"
                        class="space-y-4"
                        @submit.prevent="register"
                    >
                        <p v-if="registerErrors.general" class="bg-danger-light text-danger text-sm rounded p-2 flex justify-center items-center gap-2">
                            <Icon name="ExclamationCircle" class="h-4 w-4" />
                            {{ registerErrors.general }}
                        </p>

                        <InputField
                            v-model="registerForm.name"
                            label="Full Name"
                            icon="User"
                            type="text"
                            placeholder="Enter full name"
                            required
                        />
                        <p v-if="registerErrors.name" class="text-danger text-xs pl-1 mt-1">
                            {{ registerErrors.name }}
                        </p>

                        <InputField
                            v-model="registerForm.email"
                            label="Email"
                            icon="Envelope"
                            type="email"
                            placeholder="Enter email"
                            required
                        />
                        <p v-if="registerErrors.email" class="text-danger text-xs pl-1 mt-1">
                            {{ registerErrors.email }}
                        </p>

                        <InputField
                            v-model="registerForm.password"
                            label="Password"
                            icon="LockClosed"
                            placeholder="Enter password"
                            :type="showRegisterPassword ? 'text' : 'password'"
                            :show-toggle="Boolean(registerForm.password)"
                            required
                            @toggle="showRegisterPassword = !showRegisterPassword"
                            @input="checkPasswordStrength"
                        />
                        <p v-if="registerErrors.password" class="text-danger text-xs pl-1 mt-1">
                            {{ registerErrors.password }}
                        </p>

                        <InputField
                            v-model="registerForm.passwordConfirm"
                            label="Confirm Password"
                            icon="LockClosed"
                            placeholder="Confirm password"
                            :type="showRegisterPassword ? 'text' : 'password'"
                            required
                        />
                        <p v-if="registerErrors.passwordConfirm" class="text-danger text-xs pl-1 mt-1">
                            {{ registerErrors.passwordConfirm }}
                        </p>
                        <button
                            class="btn-primary w-full flex items-center justify-center"
                            :disabled="isNavigating"
                        >
                            <span v-if="!isNavigating">Register</span>
                            <svg
                                v-else
                                class="animate-spin h-5 w-5 text-white"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                    fill="none"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"
                                />
                            </svg>
                        </button>
                    </form>
                </Transition>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import { useRouter } from 'vue-router';
    import { useAuthStore } from '@/store/authStore';
    import InputField from '@/components/InputField.vue';
    const authStore = useAuthStore();
    const router = useRouter();

    const authMode = ref('login');
    const showLoginPassword = ref(false);
    const showRegisterPassword = ref(false);
    const isNavigating = ref(false);

    const loginForm = ref({ email: '', password: '' });
    const registerForm = ref({ name: '', email: '', password: '', passwordConfirm: '' });

    const passwordStrength = ref('');
    const loginErrors = ref({
        email: '',
        password: '',
        general: ''
    });

    const registerErrors = ref({
        name: '',
        email: '',
        password: '',
        passwordConfirm: '',
        general: ''
    });

    const EMAIL_REG_EX = /^[\w-.]+@[\w-]+\.[\w]{2,}$/;

    const checkPasswordStrength = () => {
        const password = registerForm.value.password;
        const alphaRegEx = /[A-Z]/;
        const numericRegEx = /[0-9]/;

        if (password.length < 6) {
            passwordStrength.value = 'Weak';
        } else if (password.match(alphaRegEx) && password.match(numericRegEx)) {
            passwordStrength.value = 'Strong';
        } else {
            passwordStrength.value = 'Medium';
        }
    };

    watch(() => loginForm.value.email, () => {
        loginErrors.value.email = '';
        loginErrors.value.general = '';
    });

    watch(() => loginForm.value.password, () => {
        loginErrors.value.password = '';
        loginErrors.value.general = '';
    });

    watch(() => registerForm.value.name, () => {
        registerErrors.value.name = '';
        registerErrors.value.general = '';
    });

    watch(() => registerForm.value.email, () => {
        registerErrors.value.email = '';
        registerErrors.value.general = '';
    });

    watch(() => registerForm.value.password, () => {
        registerErrors.value.password = '';
        registerErrors.value.general = '';
    });

    watch(() => registerForm.value.passwordConfirm, () => {
        registerErrors.value.passwordConfirm = '';
        registerErrors.value.general = '';
    });

    const login = () => {
        loginErrors.value = { email: '', password: '', general: '' };

        const email = loginForm.value.email.trim();
        const password = loginForm.value.password.trim();

        if (!EMAIL_REG_EX.test(email)) {
            loginErrors.value.email = 'Please enter a valid email address.';
            return;
        }

        if (password.length < 6) {
            loginErrors.value.password = 'Password must be at least 6 characters.';
            return;
        }

        const result = authStore.login(email, password);

        if (result.success) {
            router.push('/dashboard');
        } else {
            loginErrors.value.general = result.message;
        }
    };

    const register = () => {
        registerErrors.value = { name: '', email: '', password: '', passwordConfirm: '', general: '' };

        const name = registerForm.value.name.trim();
        const email = registerForm.value.email.trim();
        const password = registerForm.value.password.trim();
        const passwordConfirm = registerForm.value.passwordConfirm.trim();

        if (!name || name.length < 2) {
            registerErrors.value.name = 'Full name is required.';
            return;
        }

        if (!EMAIL_REG_EX.test(email)) {
            registerErrors.value.email = 'Please enter a valid email address.';
            return;
        }

        if (password.length < 6) {
            registerErrors.value.password = 'Password must be at least 6 characters.';
            return;
        }

        if (password !== passwordConfirm) {
            registerErrors.value.passwordConfirm = 'Passwords do not match.';
            return;
        }

        const result = authStore.register(name, email, password);

        if (result.success) {
            router.push('/dashboard');
        } else {
            registerErrors.value.general = result.message;
        }
    };
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: all 0.3s ease;
        transform: scale(1);
        opacity: 1;
    }

    .fade-enter-from, .fade-leave-to {
        opacity: 0;
        transform: translateY(10px) scale(0.95);
    }
</style>
