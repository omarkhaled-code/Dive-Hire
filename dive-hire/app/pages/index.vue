<script setup lang="ts">
const {logout} = useAuth()
const handleLogout  = async ()=> {
    await logout()
}
const {user} = useAuth()
</script>
<template>

    <div class="p-8">

        <h1>Welcome, <span v-if="user" class="text-blue-500 pr-1 font-bold">{{ user.name }}</span>  to the Home Page</h1>
        <button>
            
            <nuxt-link to="/auth" v-if="!user">Auth</nuxt-link>
            <button v-else @click="handleLogout" class="text-red-500 p-4 m-2 cursor-pointer">Logout</button>
        </button>
    </div>

</template>