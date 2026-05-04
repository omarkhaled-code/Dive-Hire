import { useAuthStore } from "~/stores/auth"


// middleware/auth.ts
export default defineNuxtRouteMiddleware(() => {
    const { isLoggedIn } = useAuthStore()

    if (!isLoggedIn) {
        return navigateTo('/auth/login')
    }
})