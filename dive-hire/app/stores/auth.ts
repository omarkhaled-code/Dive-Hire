// stores/auth.ts
import type { LoginData, RegisterData, User } from "~/types/user";

export const useAuthStore = defineStore("auth", () => {
  // ───── State ─────
  const user = ref<User | null>(null);
  const token = ref<string | null>(null);
  const loading = ref(false);
  const error = ref<string | null>(null);

  // ───── Getters (computed) ─────
  const isLoggedIn = computed(() => !!token.value);
  const role = computed(() => user.value?.role ?? null); // 'developer' | 'employer' | null
  const isDeveloper = computed(() => role.value === "developer");
  const isEmployer = computed(() => role.value === "employer");
  const isProfileComplete = computed(
    () => user.value?.profile_completed ?? false,
  );

  // ───── Register ─────
  async function register(form: RegisterData) {
    loading.value = true;
    error.value = null;
    try {
      const data = await $fetch<{ user: User; token: string }>(
        "/api/auth/register",
        {
          method: "POST",
          body: form,
        },
      );
      _setSession(data.user, data.token);
      loading.value = false;
      handleNavigate(data.user, false);
    } catch (err: any) {
      error.value = err?.data?.message ?? "Registration failed";
    }
  }

  // ───── Login ─────
  async function login(form: LoginData) {
    loading.value = true;
    error.value = null;
    try {
      const data = await $fetch<{ user: User; token: string, has_profile:boolean }>(
        "/api/auth/login",
        {
          method: "POST",
          body: form,
        },
      );
      _setSession(data.user, data.token);
      loading.value = false;
      handleNavigate(data.user, data.has_profile);
    } catch (err: any) {
      error.value = err?.data?.message ?? "Login failed";
    }
  }

  // ───── Logout ─────
  async function logout() {
    try {
      await $fetch("/api/auth/logout", {
        method: "POST",
        headers: { Authorization: `Bearer ${token.value}` },
      });
    } finally {
      _clearSession();
      navigateTo("/auth/login");
    }
  }

  // ───── Fetch current user (on app boot) ─────
  async function fetchUser() {
    if (!token.value) return;
    try {
      const data = await $fetch<{ user: User }>("/api/auth/me", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      user.value = data.user;
    } catch {
      _clearSession(); // token expired or invalid
    }
  }

  // ───── Private helpers ─────
  function _setSession(u: User, t: string) {
    user.value = u;
    token.value = t;
  }

  function _clearSession() {
    user.value = null;
    token.value = null;
  }
  function handleNavigate(user: any, hasProfile: boolean = false) {
    console.log(user);
    
    if (hasProfile === false) {
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
    // State
    user,
    token,
    loading,
    error,
    // Getters
    isLoggedIn,
    role,
    isDeveloper,
    isEmployer,
    isProfileComplete,
    // Actions
    register,
    login,
    logout,
    fetchUser,
  };
});
