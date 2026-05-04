<script setup lang="ts">
import type { RegisterData } from '~/types/user';


const emit = defineEmits(['switch-mode'])

const errors = ref<{ name?: string, email?: string; password?: string }>({})

const { validateRegister } = useValidation()

const formData = reactive<RegisterData>({
    name: '',
    email: '',
    password: '',
    role: 'developer' // Default role
})


const passwordInput = ref<HTMLInputElement | null>(null);

const { register, error, loading } = useAuth()


const handleRegister = async () => {
    const result = validateRegister(formData.email, formData.password)
    errors.value = {}
    let hasError = false;

    if (!formData.name) {
         errors.value.name = "The user name is required"
         hasError = true;
    }

    if (!result.email) {
         errors.value.email = 'The email is not correct'
         hasError = true;
    }


    if (!result.password) {
         errors.value.password = 'Password should contain at least 8 char!'
         hasError = true;
    }

    if(hasError) return;

    await register(formData)

    

}


const togglePasswordVisibility = () => {
    passwordInput.value?.type === 'password'
        ? (passwordInput.value.type = 'text')
        : (passwordInput.value!.type = 'password');
}
</script>

<template>
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-[#101322]">
        <div class="w-full max-w-[440px] flex flex-col">
            <div class="mb-10">

                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-3">Register</h2>

                <p class="text-slate-500 dark:text-slate-400">Please enter your details to access your
                    dashboard.</p>
            </div>

            <!-- Role Toggle -->
            <div
                class="flex h-12 flex-1 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800/50 p-2 mb-8">
                <label
                    class="flex cursor-pointer h-full grow items-center justify-center rounded-md p-3 has-[:checked]:bg-white dark:has-[:checked]:bg-slate-700 has-[:checked]:shadow-sm has-[:checked]:text-primary dark:has-[:checked]:text-white text-slate-600 dark:text-slate-400 text-sm font-semibold transition-all">
                    <span>Job Seeker</span>
                    <input class="invisible w-0" name="role" type="radio" value="developer" v-model="formData.role" />
                </label>
                <label
                    class="flex cursor-pointer h-full grow items-center justify-center rounded-md p-3 has-[:checked]:bg-white dark:has-[:checked]:bg-slate-700 has-[:checked]:shadow-sm has-[:checked]:text-primary dark:has-[:checked]:text-white text-slate-600 dark:text-slate-400 text-sm font-semibold transition-all">
                    <span>Recruiter</span>
                    <input class="invisible w-0" name="role" type="radio" value="employer" v-model="formData.role" />
                </label>
            </div>

            <form class="space-y-6" @submit.prevent="handleRegister">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1" for="email">Full
                        Name
                    </label>
                    <div class="relative group">
                        <input
                            class="w-full h-12 px-4 rounded-lg border  bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2b4bee]/20 focus:border-[#2b4bee] transition-all"
                            id="name" name="name" placeholder="John Doe" type="text" v-model="formData.name"
                            :class="{ 'border-red-500': errors.name, 'border-slate-200 dark:border-slate-700': !errors.name }"
                            @input="errors.name = ''" />
                        <p v-if="errors?.name" class="text-red-500">{{ errors.name }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1" for="email">Email
                        Address</label>
                    <div class="relative group">
                        <input
                            class="w-full h-12 px-4 rounded-lg border bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2b4bee]/20 focus:border-[#2b4bee] transition-all"
                            id="email" name="email" placeholder="name@company.com"  type="email" v-model="formData.email"
                            :class="{ 'border-red-500': errors.email, 'border-slate-200 dark:border-slate-700': !errors.email }"
                            @input="errors.email = ''" />
                        <p v-if="errors?.email" class="text-red-500">{{ errors.email }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300"
                            for="password">Password</label>
                    </div>
                    <div class="relative group flex items-center">
                        <input ref="passwordInput"
                            class="w-full h-12 pl-4 pr-12 rounded-lg border bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#2b4bee]/20 focus:border-[#2b4bee] transition-all"
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
                <p v-if="error" class="text-red-500 text-center">{{ error }}</p>
                <button
                    class="w-full h-12 bg-[#2b4bee] text-white font-bold rounded-lg shadow-lg shadow-[#2b4bee]/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-2 cursor-pointer"
                    type="submit">
                    <span>Sign in</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </form>

            <p class="mt-10 text-center text-sm text-slate-500">
                Already have an account?
                <button @click="emit('switch-mode')"
                    class="font-bold text-[#2b4bee] hover:underline ml-1 cursor-pointer">Sign in</button>
            </p>
        </div>


    </div>
</template>

<style scoped>

</style>