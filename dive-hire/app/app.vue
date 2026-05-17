<script setup>

const { fetchUser, user } = useAuth()




await fetchUser()




</script>
<template>
    <NuxtLayout>
        <NuxtPage />
    </NuxtLayout>
</template>
