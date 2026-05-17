import { useAuthStore } from "~/stores/auth"


// middleware/auth.ts
export default defineNuxtRouteMiddleware( async() => {
    const { fetchUser,isLoggedIn, user } =  useAuthStore()
      await fetchUser();
    
     
    
    if(isLoggedIn) {
        return navigateTo('/')
    }
})