<script setup lang="ts">
const mode = ref('login') // login or register

const {user} = useAuth()
definePageMeta({
    layout: 'auth',
    middleware: ['guest']
})

if(user.value) {
    navigateTo('/')
}


</script>

<template>
    <main class="flex-grow flex items-stretch h-[100vh]">
        <!-- Split Screen: Left Side (Branding/Social Proof) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#2b4bee] items-center justify-center p-12 overflow-hidden">
            <div class="absolute inset-0 opacity-20 pointer-events-none"
                data-alt="Subtle white grid pattern on blue background"
                style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
            </div>
            <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-blue-400/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-80 h-80 bg-blue-600/30 rounded-full blur-3xl"></div>
            <div class="relative z-10 max-w-lg text-white">
                <div
                    class="mb-10 inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl">
                    <span class="material-symbols-outlined text-4xl">rocket_launch</span>
                </div>
                <h1 class="text-5xl font-black leading-tight tracking-tight mb-6">
                    Connect with the world's best remote talent.
                </h1>
                <p class="text-blue-100 text-lg mb-12 leading-relaxed">
                    Join 50,000+ developers and global companies building the future of distributed work. Secure
                    your next role or scale your team today.
                </p>
                <div class="grid grid-cols-2 gap-8">
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                        <div class="text-3xl font-bold mb-1">12k+</div>
                        <div class="text-blue-200 text-sm">Active Jobs</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                        <div class="text-3xl font-bold mb-1">98%</div>
                        <div class="text-blue-200 text-sm">Satisfaction</div>
                    </div>
                </div>
                <div class="mt-16 flex items-center gap-4">
                    <div class="flex -space-x-3 overflow-hidden">
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#2b4bee]"
                            data-alt="Profile picture of a remote developer"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBVOY8SmbeItkfWgrJ5G1aSR8ujYq8PMUQL4dQuTINqxtNCJDfMcozjbGvRmeqRNSQwtOJlzDW3Q8Bpz1hTq9X7Yd19KG78cWiXy3J8BiaTYtVPF5rNG8C7CTIFG4Wz0Cx7A5NYMXwKXmq-i7DX5apRs3XqKKwTXyfwzYmmvaD9iwlaC6kfjkn-NU1sqb0hzhVWynf-K57VRpdGnhzFOs4byk-xy9B8ydLyPu0KdRVdXU2aVVZEnxKxnOAlzdTIk-p7K-VdBiLI6A" />
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#2b4bee]"
                            data-alt="Profile picture of a tech recruiter"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBng-_eAU1FlDkg1l3IGZB9LnLZ85Z8xDYnepkre--sbqwd07-r5Xn1n2lqpB8ZYAIceJbqjsiK9UvsS3NTPCIqLDX7bgt4iYNcNmFHVdrJZq6xLNkkvP4lAhxXt7TTdghqyDI2sumS2UcRySv4RbIyEakr5vpis1gNH7ec1xHieFI-bN2SN5vd6kxioTEpYFHkRGqjnV5Sf4CoolJd7-OYTzp-IiJKUI8T0C9Yo_c0OyGr74vNdFxagXOT_74wpoOcl6G_CX9zCFg" />
                        <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#2b4bee]"
                            data-alt="Profile picture of a software engineer"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCPdsL65fMritGIoBO2DjwOLtbmUwjAGMp9fptLi1S9gDNxG7ZZtm_OqeKReBXHblf6dyt_EXkEkV9IH7s9A8vA1pEE9ajgpWCpwZdFeGSa6AKPOv1--QqiKaSschnTh3nMXCmKrjerRTKP1iX8XxM8O54B1JddpO8TbauwWqxT5iMCGoRQlXutRQ_DuL6ZOn0KmTNfden2YEPVUWCTZB726EhtzQccFLMEZfolbS5l7sitT0IpV45bE4BN1aZ7FQqW5VLaEIwej7k" />
                    </div>
                    <p class="text-sm font-medium text-blue-100">Trusted by thousands of professionals</p>
                </div>
            </div>
        </div>

        <AuthLogin v-if="mode === 'login'" @switch-mode="mode = 'register'" />
        <AuthRegister v-else-if="mode === 'register'" @switch-mode="mode = 'login'" />

    </main>
</template>

<style scoped></style>
