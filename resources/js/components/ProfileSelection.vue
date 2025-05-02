<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    import ProfileCar from './ProfileCar.vue'
    import { RouterLink, useRoute } from 'vue-router'
    import { useRouter } from 'vue-router'

    const router = useRouter()
    const cars = ref([])

    onMounted(async () => {
        try {
            const response = await axios.get('/profile/cars')
            cars.value = response.data
        } catch (error) {
            console.error('Failed to fetch profile cars:', error)
        }

    })
</script>

<template>
    <div class="profileSelection-content">
        <div class="MyGarage-selection">
            <ProfileCar v-for="car in cars" :key="car.id" :car="car" />   
                    
            <div class="addCar">
                <RouterLink to="/addCar" class="plus-link">+</RouterLink>
            </div>                 
        </div>
    </div>
</template>
<style>
    .addCar {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px;
        width: 250px;
        height: 350px;
        border-radius: 2px;
        box-shadow: 0 0 0 2px black;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .plus-link {
        font-size: 60px;
        color: black;
        background-color: white;
        border: 2px solid black;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        transition: transform 0.2s ease;
    }

    .plus-link:hover {
        transform: scale(1.1);
    }
    .profileSelection-content {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .MyGarage-selection {
        display: flex;
        flex-wrap: wrap;
        overflow-y: auto;
        padding: 20px;
        gap: 20px;
        z-index: 1;
        position: relative;
        height: 100%;

    }

    .CarOptions ul{
        display: flex;
        justify-content: center;
    }
    .CarOptions ul li{
        margin: 25px;
        margin-inline: 100px;
        width: 160px;
        height: 60px;
        border-radius: 30px;
        border: 1px solid #000;
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .CarOptions ul li a{
        width: 100%;
        height: 100%;
        justify-content: center;
        display: flex;
        align-items: center;
    }
    .CarOptions ul li:hover{
        transform: scale(1.2);
    }
    .CarOptions .sell{
        background-color: #007bff;
    }
    .CarOptions .remove{
        background-color: #dc3545;
    }
    .CarOptions .add{
        background-color: #4CAF50;
    }
</style>