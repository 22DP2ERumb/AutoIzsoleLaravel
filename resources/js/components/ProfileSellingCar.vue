<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import ProfileCar from './ProfileCar.vue'
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
  carId: {
    type: [Number, String],
    required: true
  }
})

// Car Data
const car = ref(null)
const AuctionCar = ref(false)
const editCar = ref(false)

// Auction Form
const startingPrice = ref(0)
const buyOutPrice = ref(0)
const bidIncrement = ref(0)
const reservePrice = ref(0)
const startTime = ref(new Date().toISOString().split('T')[0])
const endTime = ref(new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0])

// Edit Form
const brands = ref([])
const models = ref([])
const error = ref('')

const newBrand = ref('')
const newModel = ref('')
const newYear = ref('')
const newMileage = ref('')
const newFuelType = ref('')
const newTransmission = ref('')
const newEngineSize = ref('')
const newBodyType = ref('')
const newColor = ref('')

// Fetch car data
const fetchCar = async () => {
  try {
    const response = await axios.get(`/getCar/${props.carId}`)
    car.value = response.data
    
    // Set form values
    newBrand.value = car.value.brand.manufacturer
    newModel.value = car.value.model.model
    newYear.value = car.value.year
    newMileage.value = car.value.mileage
    newFuelType.value = car.value.fuel_type
    newTransmission.value = car.value.transmission
    newEngineSize.value = car.value.engine_size
    newBodyType.value = car.value.body_type
    newColor.value = car.value.color

    // Get models for the car's brand
    if (car.value.brand?.id) {
      await getModels(car.value.brand.id)
    }
  } catch {
    car.value = null
  }
}

// Fetch brands
const fetchBrands = async () => {
  try {
    const response = await axios.get('/allCarBrands')
    brands.value = response.data
  } catch (error) {
    console.error('Error fetching car brands:', error)
  }
}

// Fetch models for brand
const getModels = async (brandId) => {
  try {
    const response = await axios.get(`/getModelsByBrand/${brandId}`)
    models.value = response.data
  } catch (error) {
    console.error('Error fetching car models:', error)
  }
}


// Watch for brand changes
watch(newBrand, (newVal) => {
  const selectedBrand = brands.value.find(b => b.manufacturer === newVal)
  if (selectedBrand) {
    getModels(selectedBrand.id)
  }
})

// Form validation
const validateNewValues = () => {
  if (!newBrand.value || !newModel.value) {
    error.value = 'Brand and Model are required'
    return false
  }
  if (!newYear.value || newYear.value < 1886 || newYear.value > new Date().getFullYear() + 1) {
    error.value = 'Invalid year'
    return false
  }
  if (newMileage.value < 0) {
    error.value = 'Mileage cannot be negative'
    return false
  }
  if (!newFuelType.value) {
    error.value = 'Fuel type is required'
    return false
  }
  if (!newTransmission.value) {
    error.value = 'Transmission is required'
    return false
  }
  if (newEngineSize.value < 0) {
    error.value = 'Engine size cannot be negative'
    return false
  }
  if (!newBodyType.value || !newColor.value) {
    error.value = 'Body type and color are required'
    return false
  }
  error.value = ''
  return true
}

const validateAuction = () => {
  if (!car.value?.id) {
    error.value = 'CarID is required'
    return false
  }
  if (startingPrice.value < 0) {
    error.value = 'Starting price cannot be lower than 0'
    return false
  }
  if (buyOutPrice.value < 0) {
    error.value = 'Buy Out price cannot be lower than 0'
    return false
  }
  if (buyOutPrice.value < startingPrice.value) {
    error.value = 'Buy Out price cannot be lower than starting price'
    return false
  }
  if (bidIncrement.value < 0) {
    error.value = 'Bid Increment cannot be lower than 0'
    return false
  }
  if (reservePrice.value < 0) {
    error.value = 'Reserve Price cannot be lower than 0'
    return false
  }
  if (reservePrice.value < startingPrice.value) {
    error.value = 'Reserve Price cannot be lower than Starting Price'
    return false
  }
  if (reservePrice.value > buyOutPrice.value) {
    error.value = 'Reserve Price cannot be higher than Buy Out Price'
    return false
  }
  if (!startTime.value || !endTime.value) {
    error.value = 'Start and End dates are required'
    return false
  }
  if (new Date(startTime.value) < new Date()) {
    error.value = 'Start time cannot be in the past'
    return false
  }
  if (new Date(endTime.value) <= new Date(startTime.value)) {
    error.value = 'End time must be after start time'
    return false
  }
  error.value = ''
  return true
}

// Form submissions
const EditCar = async () => {
  if (!validateNewValues()) return

  try {
    await axios.post('/updateCar', {
      car_id: car.value.id,
      car_brand: newBrand.value,
      car_model: newModel.value,
      year: newYear.value,
      mileage: newMileage.value,
      fuel_type: newFuelType.value,
      transmission: newTransmission.value,
      engine_size: newEngineSize.value.toString(),
      body_type: newBodyType.value,
      color: newColor.value,
    })
    window.location.reload()
  } catch (error) {
    console.error("Error updating car:", error)
    error.value = 'Failed to update car. Please try again.'
  }
}

const StartAuction = async () => {
  if (!validateAuction()) return

  try {
    await axios.post('/AuctionCar', {
      car_id: car.value.id,
      startingPrice: startingPrice.value,
      buyOutPrice: buyOutPrice.value,
      bidIncrement: bidIncrement.value,
      reservePrice: reservePrice.value,
      startTime: startTime.value,
      endTime: endTime.value,
    })
    router.push({ path: '/profile' })
  } catch (error) {
    console.error(error)
    error.value = 'Failed to start auction. Please try again.'
  }
}

// Initialize
watch(() => props.carId, fetchCar, { immediate: true })
onMounted(fetchBrands)

</script>

<template>
  <div class="selling-car-container">
    <!-- Car Preview -->
    <div class="car-preview">
      <ProfileCar v-if="car" :car="car" @click="AuctionCar = false; editCar = false" />
    </div>

    <!-- Main Content -->
    <div class="car-details-container">
      <!-- View Mode -->
      <div v-if="!AuctionCar && !editCar" class="view-mode">
        <div class="specifications-card">
          <h3>Car Specifications</h3>
          <i class="pi pi-pencil edit-icon" @click="editCar = true"></i>
          
          <div class="specs-grid">
            <div class="spec-item">
              <span class="spec-label">Brand:</span>
              <span class="spec-value">{{ car?.brand.manufacturer }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Model:</span>
              <span class="spec-value">{{ car?.model.model }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Year:</span>
              <span class="spec-value">{{ car?.year }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mileage:</span>
              <span class="spec-value">{{ car?.mileage?.toLocaleString() }} km</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Fuel Type:</span>
              <span class="spec-value">{{ car?.fuel_type }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Transmission:</span>
              <span class="spec-value">{{ car?.transmission }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Engine Size:</span>
              <span class="spec-value">{{ car?.engine_size }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Body Type:</span>
              <span class="spec-value">{{ car?.body_type }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Color:</span>
              <span class="spec-value">{{ car?.color }}</span>
            </div>
          </div>
        </div>

        <!-- Auction Controls -->
        <div class="auction-controls">
          <button 
            v-if="!car?.auctions?.is_active"
            class="auction-btn list-btn"
            @click="AuctionCar = true">
            List for Auction
          </button>
          <button 
            v-if="car?.auctions?.is_active"
            class="auction-btn cancel-btn"
            @click="AuctionCar = true">
            Manage Auction
          </button>
        </div>
      </div>

      <!-- Edit Mode -->
      <div v-if="editCar && !AuctionCar" class="edit-mode">
        <div class="edit-form">
          <h3>Edit Car Details</h3>
          
          <div class="form-grid">
            <div class="form-group">
              <label>Brand</label>
              <select v-model="newBrand" class="form-input">
                <option v-for="brand in brands" :key="brand.id" :value="brand.manufacturer">
                  {{ brand.manufacturer }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Model</label>
              <select v-model="newModel" class="form-input">
                <option v-for="model in models" :key="model.id" :value="model.model">
                  {{ model.model }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Year</label>
              <input type="number" v-model="newYear" class="form-input" />
            </div>

            <div class="form-group">
              <label>Mileage (km)</label>
              <input type="number" v-model="newMileage" class="form-input" />
            </div>

            <div class="form-group">
              <label>Fuel Type</label>
              <select v-model="newFuelType" class="form-input">
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
              </select>
            </div>

            <div class="form-group">
              <label>Transmission</label>
              <select v-model="newTransmission" class="form-input">
                <option value="Manual">Manual</option>
                <option value="Automatic">Automatic</option>
                <option value="CVT">CVT</option>
              </select>
            </div>

            <div class="form-group">
              <label>Engine Size (L)</label>
              <input type="number" step="0.1" v-model="newEngineSize" class="form-input" />
            </div>

            <div class="form-group">
              <label>Body Type</label>
              <input type="text" v-model="newBodyType" class="form-input" />
            </div>

            <div class="form-group">
              <label>Color</label>
              <input type="text" v-model="newColor" class="form-input" />
            </div>
          </div>

          <div class="form-actions">
            <button class="cancel-btn" @click="editCar = false">Cancel</button>
            <button class="save-btn" @click="EditCar">Save Changes</button>
          </div>
          
          <p v-if="error" class="error-message">{{ error }}</p>
        </div>
      </div>

      <!-- Auction Mode -->
      <div v-if="AuctionCar && !editCar" class="auction-mode">
        <div class="auction-form">
          <h3>{{ car?.auctions?.is_active ? 'Manage Auction' : 'Create Auction' }}</h3>
          
          <div class="form-grid">
            <div class="form-group">
              <label>Starting Price (€)</label>
              <input type="number" v-model="startingPrice" class="form-input" min="0" />
            </div>

            <div class="form-group">
              <label>Buy Out Price (€)</label>
              <input type="number" v-model="buyOutPrice" class="form-input" min="0" />
            </div>

            <div class="form-group">
              <label>Bid Increment (€)</label>
              <input type="number" v-model="bidIncrement" class="form-input" min="0" />
            </div>

            <div class="form-group">
              <label>Reserve Price (€)</label>
              <input type="number" v-model="reservePrice" class="form-input" min="0" />
            </div>

            <div class="form-group">
              <label>Start Date</label>
              <input type="date" v-model="startTime" class="form-input" :min="new Date().toISOString().split('T')[0]" />
            </div>

            <div class="form-group">
              <label>End Date</label>
              <input type="date" v-model="endTime" class="form-input" :min="startTime" />
            </div>
          </div>

          <div class="form-actions">
            <button class="cancel-btn" @click="AuctionCar = false">Cancel</button>
            <button class="save-btn" @click="StartAuction">
              {{ car?.auctions?.is_active ? 'Update Auction' : 'Start Auction' }}
            </button>
          </div>
          
          <p v-if="error" class="error-message">{{ error }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.selling-car-container {
  display: flex;
  gap: 30px;
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.car-preview {
  flex: 0 0 350px;
}

.car-details-container {
  flex: 1;
}

/* View Mode */
.view-mode {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.specifications-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  position: relative;
}

.specifications-card h3 {
  margin-top: 0;
  color: #333;
  font-size: 1.4rem;
}

.edit-icon {
  position: absolute;
  top: 25px;
  right: 25px;
  font-size: 1.2rem;
  color: #666;
  cursor: pointer;
  transition: all 0.2s;
}

.edit-icon:hover {
  color: #4361ee;
  transform: scale(1.1);
}

.specs-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-top: 20px;
}

.spec-item {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid #eee;
}

.spec-label {
  font-weight: 500;
  color: #666;
}

.spec-value {
  font-weight: 600;
  color: #333;
}

.auction-controls {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.auction-btn {
  padding: 12px 25px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.list-btn {
  background: #4361ee;
  color: white;
}

.list-btn:hover {
  background: #3a56d4;
  transform: translateY(-2px);
}

.cancel-btn {
  background: #dc3545;
  color: white;
}

.cancel-btn:hover {
  background: #c82333;
  transform: translateY(-2px);
}

/* Edit & Auction Modes */
.edit-mode, .auction-mode {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.edit-form h3, .auction-form h3 {
  margin-top: 0;
  color: #333;
  font-size: 1.4rem;
  margin-bottom: 25px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #555;
}

.form-input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #4361ee;
  box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
}

.save-btn {
  padding: 12px 25px;
  background: #4361ee;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.save-btn:hover {
  background: #3a56d4;
  transform: translateY(-2px);
}

.error-message {
  color: #dc3545;
  margin-top: 20px;
  text-align: center;
}

/* Responsive */
@media (max-width: 992px) {
  .selling-car-container {
    flex-direction: column;
  }
  
  .car-preview {
    flex: 0 0 auto;
    margin-bottom: 20px;
  }
  
  .specs-grid, .form-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 576px) {
  .selling-car-container {
    padding: 15px;
  }
  
  .specifications-card, .edit-form, .auction-form {
    padding: 20px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .save-btn, .cancel-btn {
    width: 100%;
  }
}
</style>