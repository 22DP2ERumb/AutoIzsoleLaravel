<script setup>
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'
    import ProfileContainer from '../components/ProfileContainer.vue'
    import Header from '../components/Header.vue'
    

    const router = useRouter();

    const isAuthenticated = ref(false);

    onMounted(async () => {
      
        const response = await axios.get('/auth-user');
        isAuthenticated.value = response.data.isAuthenticated

        if (!response.data.isAuthenticated) {
            router.push('/login');
        }
    })
</script>

<template>
    <main>
        <Header />
        <ProfileContainer/>
    </main>
</template>
