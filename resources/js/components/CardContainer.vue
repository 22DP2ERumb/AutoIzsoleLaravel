<script setup>
import CarCard from './CarCard.vue'
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const cars = ref([])
const carBrands = ref([])
const selectedBrand = ref('all')
const selectedModel = ref('all')
const searchQuery = ref('')
const selectedSort = ref('')
const isLoading = ref(false)

const sortOptions = [
  { value: '', text: 'Default' },
  { value: 'year_asc', text: 'Year (Oldest First)' },
  { value: 'year_desc', text: 'Year (Newest First)' },
  { value: 'mileage_asc', text: 'Mileage (Lowest First)' },
  { value: 'mileage_desc', text: 'Mileage (Highest First)' }
]

onMounted(async () => {
    try {
        isLoading.value = true
        const brandsResponse = await axios.get('/allCarBrands')
        carBrands.value = brandsResponse.data
        await fetchCars()
    } catch (error) {
        console.error('Failed to fetch brands:', error)
    } finally {
        isLoading.value = false
    }
})

const fetchCars = async () => {
    try {
        isLoading.value = true
        const params = {
            brand_id: selectedBrand.value,
            model_id: selectedModel.value,
            search: searchQuery.value,
            sort_by: selectedSort.value
        }
        
        const response = await axios.get('/filteredCars', { params })
        cars.value = response.data
    } catch (error) {
        console.error('Failed to fetch cars:', error)
    } finally {
        isLoading.value = false
    }
}

watch([selectedBrand, selectedModel, searchQuery, selectedSort], () => {
    fetchCars()
})

const models = ref([])
watch(selectedBrand, async (newBrandId) => {
    selectedModel.value = 'all'
    
    if (newBrandId === 'all') {
        models.value = []
        return
    }
    
    try {
        const response = await axios.get(`/getModelsByBrand/${newBrandId}`)
        models.value = response.data
    } catch (error) {
        console.error('Failed to fetch models:', error)
        models.value = []
    }
})
</script>

<template>
    <div class="car-list-container">
        <div class="filters-panel">
            <div class="filter-control">
                <label>Brand</label>
                <select v-model="selectedBrand" class="filter-select">
                    <option value="all">All Brands</option>
                    <option 
                        v-for="brand in carBrands" 
                        :key="brand.id" 
                        :value="brand.id"
                    >
                        {{ brand.manufacturer }}
                    </option>
                </select>
            </div>

            <div class="filter-control" v-if="selectedBrand !== 'all' && models.length">
                <label>Model</label>
                <select v-model="selectedModel" class="filter-select">
                    <option value="all">All Models</option>
                    <option 
                        v-for="model in models" 
                        :key="model.id" 
                        :value="model.id"
                    >
                        {{ model.model }}
                    </option>
                </select>
            </div>

            <div class="filter-control">
                <label>Search</label>
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    placeholder="Search..."
                    class="filter-input"
                >
            </div>

            <div class="filter-control">
                <label>Sort By</label>
                <select v-model="selectedSort" class="filter-select">
                    <option 
                        v-for="option in sortOptions" 
                        :key="option.value" 
                        :value="option.value"
                    >
                        {{ option.text }}
                    </option>
                </select>
            </div>
        </div>

        <div v-if="isLoading" class="loading-indicator">
            <div class="spinner"></div>
            <span>Loading cars...</span>
        </div>

        <div v-else class="cars-grid">
            <CarCard 
                v-for="car in cars" 
                :key="car.id" 
                :car="car" 
            />
            
            <div v-if="!cars.length" class="empty-state">
                <h3>No cars match your filters</h3>
                <p>Try adjusting your search criteria</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.car-list-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.filters-panel {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.filter-control {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-control label {
    font-weight: 600;
    font-size: 14px;
    color: #555;
}

.filter-select, .filter-input {
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
}

.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.loading-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    gap: 15px;
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

.empty-state {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    text-align: center;
}

.empty-state img {
    width: 150px;
    height: auto;
    margin-bottom: 20px;
    opacity: 0.7;
}

.empty-state h3 {
    font-size: 18px;
    color: #333;
    margin-bottom: 8px;
}

.empty-state p {
    color: #777;
    font-size: 14px;
}

@media (max-width: 768px) {
    .filters-panel {
        grid-template-columns: 1fr;
    }
    
    .cars-grid {
        grid-template-columns: 1fr;
    }
}
</style>