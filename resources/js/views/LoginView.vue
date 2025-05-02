<script setup>
    import {onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'
    import LoginContainer from '../components/LoginContainer.vue'

    const router = useRouter()

    onMounted(async () => {

        const response = await axios.get('/auth-user')

        if (response.data.isAuthenticated) {
            router.push('/')
        }
    })
    
</script>

<template>
    <main>
        <LoginContainer/>
    </main>
</template>