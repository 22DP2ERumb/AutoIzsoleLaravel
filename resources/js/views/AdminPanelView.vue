<script setup>
    import Header from '../components/Header.vue'
    import AdminPanelContainer from '../components/AdminPanelContainer.vue'
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'

    const router = useRouter();

    const isAuthenticated = ref(false);
    const isAdmin = ref(false);

    onMounted(async () => {
        try {
            const response = await axios.get('/auth-user')
            isAuthenticated.value = response.data.isAuthenticated

           isAdmin.value = response.data.user?.isAdmin

            if (!isAuthenticated.value || !isAdmin) {
                router.push('/')
            }
        } catch (error) {
            router.push('/') // fallback in case of request failure
        }
    })
</script>
<template>
    <main>
        <AdminPanelContainer/>
    </main>
</template>