import type { LoginData, RegisterData, User } from "~/types/user";

export const useAuthStore = defineStore("auth", () => {
  /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

  const user = ref<User | null>(null);

  const loading = ref(false);

  const error = ref<string | null>(null);

  /*
        |--------------------------------------------------------------------------
        | Getters
        |--------------------------------------------------------------------------
        */

  const isLoggedIn = computed(() => !!user.value);

  const role = computed(() => user.value?.role ?? null);

  const isDeveloper = computed(() => role.value === "developer");

  const isEmployer = computed(() => role.value === "employer");

  const isProfileComplete = computed(
    () => user.value?.profile_completed ?? false,
  );

  /*
        |--------------------------------------------------------------------------
        | Clear Error
        |--------------------------------------------------------------------------
        */

  function clearError() {
    error.value = null;
  }

  /*
        |--------------------------------------------------------------------------
        | Register
        |--------------------------------------------------------------------------
        */

  async function register(form: RegisterData) {
    loading.value = true;
    error.value = null;

    try {
      const data = await $fetch<{
        user: User;
      }>("/api/auth/register", {
        method: "POST",

        body: form,

        credentials: "include",
      });

      user.value = data.user;

      handleNavigate(data.user, false);
    } catch (err: any) {
      error.value =  "Registration Failed, Unprocessable Content";
    } finally {
      loading.value = false;
    }
  }

  /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

  async function login(form: LoginData) {
    loading.value = true;
    error.value = null;

    try {
      const data = await $fetch<{
        user: User;
        has_profile: boolean;
      }>("/api/auth/login", {
        method: "POST",

        body: form,

        credentials: "include",
      });

      user.value = data.user;

      handleNavigate(data.user, data.has_profile);
    } catch (err: any) {
      // error.value = err?.data?.message ?? "Login failed";
      error.value = "Invalid Credentials!";

      
    } finally {
      loading.value = false;
    }
  }

  /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

  async function logout() {
    try {
      await $fetch("/api/auth/logout", {
        method: "POST",

        credentials: "include",
      });
      user.value = null;
      navigateTo("/auth");
    } catch (error) {
        return error
    }
  }

  /*
        |--------------------------------------------------------------------------
        | Fetch User
        |--------------------------------------------------------------------------
        */

  async function fetchUser() {
    try {
      const data = await $fetch<{
        user: User;
      }>("/api/auth/me", {
        credentials: "include",
      });

      user.value = data.user;
    } catch {
      user.value = null;
    }
  }

  /*
        |--------------------------------------------------------------------------
        | Navigation
        |--------------------------------------------------------------------------
        */

  function handleNavigate(user: User, hasProfile: boolean = false) {
    if (!hasProfile) {
      if (user.role === "developer") {
        navigateTo("/developer/profile/setup");
      } else if (user.role === "employer") {
        navigateTo("/employer/profile/setup");
      }
    } else {
      navigateTo("/jobs");
    }
  }

  return {
    /*
            |--------------------------------------------------------------------------
            | State
            |--------------------------------------------------------------------------
            */

    user,
    loading,
    error,

    /*
            |--------------------------------------------------------------------------
            | Getters
            |--------------------------------------------------------------------------
            */

    isLoggedIn,
    role,
    isDeveloper,
    isEmployer,
    isProfileComplete,

    /*
            |--------------------------------------------------------------------------
            | Actions
            |--------------------------------------------------------------------------
            */

    register,
    login,
    logout,
    fetchUser,
    clearError,
  };
});
