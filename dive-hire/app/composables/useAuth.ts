// composables/useAuth.ts
// ✅ This is just a BRIDGE — thin layer over the store

import { useAuthStore } from "~/stores/auth"


export const useAuth = () => {
    const store = useAuthStore() // 👈 connects to pinia here

    return {
        // expose state
        user: computed(() => store.user),
        role: computed(() => store.role),
        loading: computed(() => store.loading),
        error: computed(() => store.error),
        isLoggedIn: computed(() => store.isLoggedIn),
        isProfileComplete: computed(() => store.isProfileComplete),

        // expose actions
        register: store.register,
        login: store.login,
        logout: store.logout,
        fetchUser: store.fetchUser,
    }
}