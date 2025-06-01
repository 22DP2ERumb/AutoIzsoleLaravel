<script setup>
import CarCard from './CarCard.vue'
import { ref, onMounted, watch } from 'vue';
import axios from 'axios'

const cars = ref([])
const carBrands = ref([])
const selectedBrand = ref('all')
const selectedModel = ref('all')
const searchQuery = ref('')
const selectedSort = ref('')

// Sorting options
const sortOptions = [
  { value: '', text: 'Default' },
  { value: 'year_asc', text: 'Year (Oldest First)' },
  { value: 'year_desc', text: 'Year (Newest First)' },
  { value: 'mileage_asc', text: 'Mileage (Lowest First)' },
  { value: 'mileage_desc', text: 'Mileage (Highest First)' }
]

// Fetch brands on mount
onMounted(async () => {
    try {
        const brandsResponse = await axios.get('/allCarBrands')
        carBrands.value = brandsResponse.data
        fetchCars()
    } catch (error) {
        console.error('Failed to fetch brands:', error)
    }
})

// Fetch cars with current filters and sorting
const fetchCars = async () => {
    try {
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
    }
}

// Watch for filter/sort changes and refetch cars
watch([selectedBrand, selectedModel, searchQuery, selectedSort], () => {
    fetchCars()
})

// Get models when brand changes
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
    <div class="filter-container">
        <div class="filter-group">
            <label for="brand-filter">Brand:</label>
            <select id="brand-filter" v-model="selectedBrand">
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

        <div class="filter-group" v-if="selectedBrand !== 'all' && models.length">
            <label for="model-filter">Model:</label>
            <select id="model-filter" v-model="selectedModel">
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

        <div class="filter-group">
            <label for="search">Search:</label>
            <input 
                id="search" 
                type="text" 
                v-model="searchQuery" 
                placeholder="Search brand or model..."
            >
        </div>

        <div class="filter-group">
            <label for="sort">Sort By:</label>
            <select id="sort" v-model="selectedSort">
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

    <div class="card-container">
        <CarCard 
            v-for="car in cars" 
            :key="car.id" 
            :car="car" 
        />
        <div v-if="!cars.length" class="no-results">
            No cars match your filters.
        </div>
    </div>
</template>

<style scoped>
.card-container {
    display: flex;
    justify-content: center;
    margin: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.filter-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 20px;
    flex-wrap: wrap;
    padding: 15px;
    background-color: #f5f5f5;
    border-radius: 8px;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-group label {
    font-weight: bold;
    color: #333;
}

.filter-group select, .filter-group input {
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #ddd;
    background-color: white;
    font-size: 14px;
}

.filter-group select {
    min-width: 150px;
}

.filter-group input {
    min-width: 200px;
}

.no-results {
    text-align: center;
    width: 100%;
    padding: 20px;
    font-size: 18px;
    color: #666;
}
</style>