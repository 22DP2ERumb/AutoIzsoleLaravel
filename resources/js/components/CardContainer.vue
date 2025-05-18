<script setup>
    import CarCard from './CarCard.vue'
    import { ref, onMounted, watch } from 'vue';
    import axios from 'axios'
    import { RouterLink, useRoute } from 'vue-router'
    import { useRouter } from 'vue-router'

    const cars = ref([])

    onMounted(async () => {     
        try {
            const response = await axios.get('/carAuctions')
            cars.value = response.data
        }catch (error){
            console.error('Failed to fetch cars:', error)
        }})

</script>

<template>
     <div class="card-container">

        <CarCard v-for="car in cars" :key="car.id" :car="car" />
    
    </div>
</template>

<style>
    .card-container{
        display: flex;
        justify-content: center;
        margin: 30px;
        flex-wrap: wrap;
    }
</style>