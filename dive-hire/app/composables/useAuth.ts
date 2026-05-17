import { useAuthStore } from "~/stores/auth"

export const useAuth = () => {

    const store = useAuthStore()

    return {

        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

        user: computed(() => store.user),

        role: computed(() => store.role),

        loading: computed(() => store.loading),

        error: computed(() => store.error),

        isLoggedIn: computed(() => store.isLoggedIn),

        isProfileComplete: computed(
            () => store.isProfileComplete
        ),

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

        register: store.register,

        login: store.login,

        logout: store.logout,

        fetchUser: store.fetchUser,

        clearError: store.clearError,
    }
}