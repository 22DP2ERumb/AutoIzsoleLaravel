<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios'
import ProfileCar from './ProfileCar.vue'
import { useRoute } from 'vue-router'

const route = useRoute();
const cars = ref([])
const isLoading = ref(true)

onMounted(async () => {
    try {
        const response = await axios.get('/profile/cars')
        cars.value = response.data
    } catch (error) {
        console.error('Failed to fetch profile cars:', error)
    } finally {
        isLoading.value = false
    }
})
</script>

<template>
    <div class="profile-selection">
        <div class="garage-view">
            <div v-if="isLoading" class="loading-state">
                <div class="spinner"></div>
                <span>Loading your cars...</span>
            </div>
            
            <div v-else class="cars-container">
                <ProfileCar 
                    v-for="car in cars" 
                    :key="car.id" 
                    :car="car" 
                    class="car-item"
                />
                
                <RouterLink 
                    to="/addCar" 
                    class="add-car-card"
                >
                    <div class="add-car-content">
                        <span class="plus-icon">+</span>
                        <span>Add Car</span>
                    </div>
                </RouterLink>
            </div>
        </div>
        
        <RouterView /> <!-- This will render ProfileSellingCar when on /profile/:carId -->
    </div>
</template>

<style scoped>
.profile-selection {
    width: 100%;
    height: 100%;
    padding: 20px;
}

.garage-view {
    width: 100%;
    height: 100%;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    gap: 15px;
    color: #555;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(67, 97, 238, 0.1);
    border-left-color: #4361ee;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.cars-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 10px;
    justify-content: flex-start;
    align-items: stretch;
}

.car-item {
    flex: 0 0 calc(25% - 20px); /* 4 items per row */
    min-width: 250px;
}

.add-car-card {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    min-height: 350px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #333;
    flex: 0 0 calc(25% - 20px);
    min-width: 250px;
}

.add-car-card:hover {
    background: #f1f3f5;
    border-color: #4361ee;
    transform: translateY(-2px);
}

.add-car-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.plus-icon {
    font-size: 3rem;
    font-weight: 200;
    color: #4361ee;
}

@media (max-width: 1200px) {
    .car-item,
    .add-car-card {
        flex: 0 0 calc(33.33% - 20px); /* 3 items per row */
    }
}

@media (max-width: 900px) {
    .car-item,
    .add-car-card {
        flex: 0 0 calc(50% - 20px); /* 2 items per row */
    }
}

@media (max-width: 600px) {
    .car-item,
    .add-car-card {
        flex: 0 0 100%; /* 1 item per row */
    }
    
    .profile-selection {
        padding: 10px;
    }
    
    .cars-container {
        justify-content: center;
    }
}
</style>