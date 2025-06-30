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
                            @toggle="toggleLoginPassword"
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
                            @toggle="toggleRegisterPassword"
                            @input="checkPasswordStrength"
                        />
                        <div v-if="registerForm.password" class="mt-1 px-1">
                            <div class="w-full h-2 rounded bg-gray-200 overflow-hidden">
                                <div
                                    class="h-full transition-all duration-300"
                                    :class="{
                                        'bg-red-500 w-1/3': passwordStrength === 'Weak',
                                        'bg-yellow-500 w-2/3': passwordStrength === 'Medium',
                                        'bg-green-500 w-full': passwordStrength === 'Strong'
                                    }"
                                ></div>
                            </div>
                            <p
                                class="text-xs mt-1"
                                :class="{
                                    'text-red-500': passwordStrength === 'Weak',
                                    'text-yellow-500': passwordStrength === 'Medium',
                                    'text-green-600': passwordStrength === 'Strong'
                                }"
                            >
                                Strength: {{ passwordStrength }}
                            </p>
                        </div>
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
                        <div class="flex items-center justify-between mt-4">
                            <label for="is_admin" class="text-sm text-text">
                                Register as Admin
                            </label>
                            <button
                                class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 focus:outline-none"
                                :class="registerForm.is_admin ? 'bg-primary' : 'bg-gray-300'"
                                type="button"
                                role="switch"
                                :aria-checked="registerForm.is_admin"
                                @click="registerForm.is_admin = !registerForm.is_admin"
                            >
                                <span
                                    class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-300"
                                    :class="registerForm.is_admin ? 'translate-x-6' : 'translate-x-1'"
                                />
                            </button>
                        </div>
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

    const isNavigating = ref(false);
    const authMode = ref('login');
    const showLoginPassword = ref(false);
    const showRegisterPassword = ref(false);

    let loginToggleTimeout = null;
    let registerToggleTimeout = null;

    const loginForm = ref({ email: '', password: '' });
    const registerForm = ref({
        name: '', email: '', password: '', passwordConfirm: '', is_admin: false
    });

    const passwordStrength = ref('');
    const loginErrors = ref({ email: '', password: '', general: '' });
    const registerErrors = ref({ name: '', email: '', password: '', passwordConfirm: '', general: '' });

    const EMAIL_REG_EX = /^[\w-.]+@[\w-]+\.[\w]{2,}$/;

    const toggleLoginPassword = () => {
        showLoginPassword.value = !showLoginPassword.value;
        clearTimeout(loginToggleTimeout);
        if (showLoginPassword.value) {
            loginToggleTimeout = setTimeout(() => showLoginPassword.value = false, 3000);
        }
    };

    const toggleRegisterPassword = () => {
        showRegisterPassword.value = !showRegisterPassword.value;
        clearTimeout(registerToggleTimeout);
        if (showRegisterPassword.value) {
            registerToggleTimeout = setTimeout(() => showRegisterPassword.value = false, 3000);
        }
    };

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

    // Field error resetters
    watch(() => loginForm.value.email, () => loginErrors.value.email = '');
    watch(() => loginForm.value.password, () => loginErrors.value.password = '');
    watch(() => registerForm.value.name, () => registerErrors.value.name = '');
    watch(() => registerForm.value.email, () => registerErrors.value.email = '');
    watch(() => registerForm.value.password, () => registerErrors.value.password = '');
    watch(() => registerForm.value.passwordConfirm, () => registerErrors.value.passwordConfirm = '');
    watch(authMode, () => {
        loginErrors.value = { email: '', password: '', general: '' };
        registerErrors.value = { name: '', email: '', password: '', passwordConfirm: '', general: '' };
    });

    const login = async () => {
        loginErrors.value = { email: '', password: '', general: '' };
        const { email, password } = loginForm.value;

        if (!EMAIL_REG_EX.test(email)) {
            loginErrors.value.email = 'Please enter a valid email address.';
            return;
        }

        if (password.length < 6) {
            loginErrors.value.password = 'Password must be at least 6 characters.';
            return;
        }

        isNavigating.value = true;
        const result = await authStore.login(email, password);

        if (result.success) {
            router.push('/dashboard');
        } else {
            loginErrors.value.general = result.message;

            if (result.errors?.email) {
                loginErrors.value.email = result.errors.email[0];
            }

            if (result.errors?.password) {
                loginErrors.value.password = result.errors.password[0];
            }
        }

        isNavigating.value = false;
    };

    const register = async () => {
        registerErrors.value = { name: '', email: '', password: '', passwordConfirm: '', general: '' };
        const { name, email, password, passwordConfirm } = registerForm.value;

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

        try {
            isNavigating.value = true;
            await authStore.register(name, email, password, passwordConfirm, registerForm.value.is_admin);
            router.push('/dashboard');
        } catch (err) {
            registerErrors.value.general = err?.message || 'Registration failed.';
        } finally {
            isNavigating.value = false;
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
