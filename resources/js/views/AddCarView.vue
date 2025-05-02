<script setup>
    import AddCarContainer from '../components/AddCarContainer.vue'
    import Header from '../components/Header.vue'
    import { useRouter } from 'vue-router'
    import { onMounted } from 'vue'
    import axios from 'axios'

    const router = useRouter()

    onMounted(async () => {

    const response = await axios.get('/auth-user');

    if (!response.data.isAuthenticated) {
        router.push('/')
    }
    })
</script>

<template>
  <main>
      <Header/>
      <AddCarContainer/>
  </main>  
</template>
  
<style scoped>

</style>
  