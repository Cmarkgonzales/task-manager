<template>
    <div class="min-h-screen bg-bg flex flex-col">
        <Navbar />
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
                        <InputField
                            v-model="loginForm.email"
                            label="Email"
                            icon="Envelope"
                            type="email"
                            placeholder="Enter email"
                            required
                        />
                        <InputField
                            v-model="loginForm.password"
                            label="Password"
                            icon="LockClosed"
                            placeholder="Enter password"
                            :type="showLoginPassword ? 'text' : 'password'"
                            :show-toggle="true"
                            required
                            @toggle="showLoginPassword = !showLoginPassword"
                        />
                        <button class="btn-primary w-full">
                            Sign In
                        </button>
                    </form>

                    <form
                        v-else
                        key="register"
                        class="space-y-4"
                        @submit.prevent="register"
                    >
                        <InputField
                            v-model="registerForm.name"
                            label="Full Name"
                            icon="User"
                            type="text"
                            placeholder="Enter full name"
                            required
                        />
                        <InputField
                            v-model="registerForm.email"
                            label="Email"
                            icon="Envelope"
                            type="email"
                            placeholder="Enter email"
                            required
                        />
                        <InputField
                            v-model="registerForm.password"
                            label="Password"
                            icon="LockClosed"
                            placeholder="Enter password"
                            :type="showRegisterPassword ? 'text' : 'password'"
                            :show-toggle="true"
                            required
                            @toggle="showRegisterPassword = !showRegisterPassword"
                            @input="checkPasswordStrength"
                        />
                        <div v-if="registerForm.password" class="text-sm">
                            <div class="h-2 w-full rounded bg-gray-200 overflow-hidden">
                                <div
                                    class="h-full transition-all duration-300"
                                    :class="{
                                        'bg-danger w-1/3': passwordStrength === 'weak',
                                        'bg-warning w-2/3': passwordStrength === 'medium',
                                        'bg-success w-full': passwordStrength === 'strong',
                                    }"
                                ></div>
                            </div>
                            <p
                                class="mt-1 text-xs font-medium"
                                :class="{
                                    'text-danger': passwordStrength === 'weak',
                                    'text-warning': passwordStrength === 'medium',
                                    'text-success': passwordStrength === 'strong',
                                }"
                            >
                                Strength: {{ passwordStrength }}
                            </p>
                        </div>

                        <InputField
                            v-model="registerForm.passwordConfirm"
                            label="Confirm Password"
                            icon="LockClosed"
                            placeholder="Confirm password"
                            :type="showRegisterPassword ? 'text' : 'password'"
                            required
                        />
                        <button class="btn-primary w-full">
                            Register
                        </button>
                    </form>
                </Transition>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import InputField from '@/components/InputField.vue';
    import Navbar from '@/components/Navbar.vue';

    const authMode = ref('login');
    const showLoginPassword = ref(false);
    const showRegisterPassword = ref(false);

    const loginForm = ref({ email: '', password: '' });
    const registerForm = ref({ name: '', email: '', password: '', passwordConfirm: '' });

    const passwordStrength = ref('');

    const checkPasswordStrength = () => {
        const password = registerForm.value.password;
        const alphaRegEx = /[A-Z]/;
        const numericRegEx = /[0-9]/;

        if (password.length < 6) {
            passwordStrength.value = 'weak';
        } else if (password.match(alphaRegEx) && password.match(numericRegEx)) {
            passwordStrength.value = 'strong';
        } else {
            passwordStrength.value = 'medium';
        }
    };

    const login = () => console.log('Login:', loginForm.value);
    const register = () => console.log('Register:', registerForm.value);
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
