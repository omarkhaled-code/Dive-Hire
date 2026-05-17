<script setup lang="ts">

const emit = defineEmits(['switch-mode'])

const formData = reactive({
    email: '',
    password: ''
})

const errors = ref<{
    email?: string
    password?: string
}>({})

const passwordInput =
    ref<HTMLInputElement | null>(null)

const { validateLogin } = useValidation()

const {
    login,
    error,
    loading,
    clearError
} = useAuth()

const handleLogin = async () => {

    /*
    |--------------------------------------------------------------------------
    | Reset Errors
    |--------------------------------------------------------------------------
    */

    clearError()

    errors.value = {}

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    const result = validateLogin(
        formData.email,
        formData.password
    )

    if (!result.email) {

        errors.value.email =
            'The email is not correct'

        return

    }

    if (!result.password) {

        errors.value.password =
            'Password should contain at least 8 characters'

        return

    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    await login(formData)

}

const togglePasswordVisibility = () => {

    if (!passwordInput.value) return

    passwordInput.value.type =
        passwordInput.value.type === 'password'
            ? 'text'
            : 'password'
}
</script>
<template>
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-[#101322]">
        <div class="w-full max-w-[440px] flex flex-col">
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Welcome back</h2>
                <p class="text-slate-500 dark:text-slate-400">Please enter your details to access your
                    dashboard.</p>
            </div>
            <form class="space-y-6" @submit.prevent="handleLogin">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2" for="email">Email
                        Address</label>
                    <div class="relative group">
                        <input
                            class="w-full h-12 px-4 rounded-lg border  bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2b4bee]/20 focus:border-[#2b4bee] transition-all"
                            id="email" name="email" placeholder="name@company.com" type="email" v-model="formData.email"
                            :class="{ 'border-red-500': errors.email, 'border-slate-200 dark:border-slate-700': !errors.email }"
                            @input="errors.email = ''" />
                        <p v-if="errors?.email" class="text-red-500">{{ errors.email }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                            for="password">Password</label>
                        <a class="text-sm font-bold text-[#2b4bee] hover:underline" href="#">Forgot password?</a>
                    </div>
                    <div class="relative group flex items-center">
                        <input ref="passwordInput"
                            class="w-full h-12 pl-4 pr-12 rounded-lg border  bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2b4bee]/20 focus:border-[#2b4bee] transition-all"
                            id="password" name="password" placeholder="••••••••" type="password"
                            v-model="formData.password"
                            :class="{ 'border-red-500': errors.password, 'border-slate-200 dark:border-slate-700': !errors.password }"
                            @input="errors.password = ''" />

                        <button
                            class="absolute right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                            type="button" aria-label="Toggle password visibility" @click="togglePasswordVisibility">
                            <span class="material-symbols-outlined text-[22px]">visibility</span>
                        </button>
                    </div>
                    <p v-if="errors?.password" class="text-red-500">{{ errors.password }}</p>
                </div>


                <p v-if="error" class="text-red-500 text-center font-bold">
                    {{ error }}
                </p>

                <button
                    class="w-full h-12 bg-[#2b4bee] text-white font-bold rounded-lg shadow-lg shadow-[#2b4bee]/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 cursor-pointer"
                    type="submit" :disabled="loading">
                    <span>Sign in</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </form>


            <div class="mt-8 relative">
                <div aria-hidden="true" class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-[#101322] text-slate-500">Or continue with</span>
                </div>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-4">
                <button
                    class="flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                    <svg class="w-5 h-5" viewbox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4"></path>
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853"></path>
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05"></path>
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Google</span>
                </button>
                <button
                    class="flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                    <svg class="w-5 h-5 dark:fill-white" fill="currentColor" viewbox="0 0 24 24">
                        <path
                            d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">GitHub</span>
                </button>
            </div>
            <p class="mt-10 text-center text-sm text-slate-500">
                Don't have an account?
                <button @click="emit('switch-mode')"
                    class="font-bold text-[#2b4bee] hover:underline ml-1 cursor-pointer">Register now</button>
            </p>
        </div>
    </div>
</template>

<style scoped></style>