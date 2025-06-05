<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const images = ref([])
const fileInput = ref(null)

// Form fields
const formFields = ref({
  brand: '',
  model: '',
  year: '',
  mileage: '',
  fuel_type: '',
  transmission: '',
  engine_size: '',
  body_type: '',
  color: ''
})

const error = ref('')
const brands = ref([])
const models = ref([])
const isLoading = ref(false)

// Fetch brands on mount
onMounted(async () => {
  try {
    const response = await axios.get('/allCarBrands')
    brands.value = response.data
  } catch (error) {
    console.error('Error fetching car brands:', error)
    error.value = 'Failed to load car brands. Please refresh the page.'
  }
})

// Watch for brand changes to load models
watch(() => formFields.value.brand, (newVal) => {
  const selectedBrand = brands.value.find(b => b.manufacturer === newVal)
  if (selectedBrand) {
    getModels(selectedBrand.id)
  }
})

const getModels = async (brandId) => {
  try {
    const response = await axios.get(`/getModelsByBrand/${brandId}`)
    models.value = response.data
    formFields.value.model = '' // Reset model when brand changes
  } catch (error) {
    console.error('Error fetching car models:', error)
    error.value = 'Failed to load car models. Please try again.'
  }
}

const triggerUpload = () => {
  fileInput.value.click()
}

const handleImageUpload = (event) => {
  const files = event.target.files
  if (!files.length) return

  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    if (!file.type.match('image.*')) continue

    const reader = new FileReader()
    reader.onload = (e) => {
      images.value.push({
        file,
        preview: e.target.result
      })
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = (index) => {
  images.value.splice(index, 1)
}

const submitForm = async () => {
  if (!validateForm()) return
  
  isLoading.value = true
  
  const formData = new FormData()
  
  // Append form data
  Object.entries(formFields.value).forEach(([key, value]) => {
    formData.append(key, value)
  })
  
  // Append images
  images.value.forEach((imgObj, index) => {
    formData.append(`images[${index}]`, imgObj.file)
  })

  try {
    await axios.post('/addCar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    router.push('/profile')
  } catch (err) {
    console.error('Error:', err)
    error.value = 'Failed to submit form. Please try again.'
  } finally {
    isLoading.value = false
  }
}

const validateForm = () => {
  const requiredFields = [
    { field: 'brand', message: 'Brand is required' },
    { field: 'model', message: 'Model is required' },
    { field: 'year', message: 'Valid year is required', numeric: true },
    { field: 'mileage', message: 'Valid mileage is required', numeric: true },
    { field: 'fuel_type', message: 'Fuel type is required' },
    { field: 'transmission', message: 'Transmission is required' },
    { field: 'engine_size', message: 'Valid engine size is required', numeric: true },
    { field: 'body_type', message: 'Body type is required' },
    { field: 'color', message: 'Color is required' }
  ]

  for (const { field, message, numeric } of requiredFields) {
    if (!formFields.value[field] || (numeric && isNaN(formFields.value[field]))) {
      error.value = message
      return false
    }
  }

  if (images.value.length === 0) {
    error.value = 'At least one image is required'
    return false
  }

  error.value = ''
  return true
}
</script>

<template>
  <div class="form-wrapper">
    <h1 class="form-title">Add Your Vehicle</h1>
    <form @submit.prevent="submitForm" class="form-container">
      <div class="form-sections">
        <section class="image-section">
          <div class="image-upload-container">
            <div 
              class="image-dropzone" 
              @click="triggerUpload"
              @dragover.prevent
              @drop.prevent="handleImageUpload($event)"
            >
              <input
                type="file"
                accept="image/*"
                @change="handleImageUpload"
                ref="fileInput"
                class="file-input"
                multiple
              />
              <div class="dropzone-content">
                <svg class="upload-icon" viewBox="0 0 24 24">
                  <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                <p>Click to upload or drag and drop</p>
                <p class="hint-text">Upload at least 1 photo (max 10)</p>
              </div>
            </div>
            
            <div v-if="images.length" class="image-previews">
              <div v-for="(img, index) in images" :key="index" class="image-preview">
                <img :src="img.preview" class="preview-image" />
                <button 
                  type="button" 
                  class="remove-image-btn"
                  @click.stop="removeImage(index)"
                >
                  &times;
                </button>
              </div>
            </div>
          </div>
        </section>

        <section class="details-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="brand">Brand</label>
              <select 
                id="brand" 
                v-model="formFields.brand"
                class="form-control"
              >
                <option value="" disabled selected>Select brand</option>
                <option 
                  v-for="brand in brands" 
                  :key="brand.id" 
                  :value="brand.manufacturer"
                >
                  {{ brand.manufacturer }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label for="model">Model</label>
              <select 
                id="model" 
                v-model="formFields.model"
                class="form-control"
                :disabled="!formFields.brand"
              >
                <option value="" disabled selected>Select model</option>
                <option 
                  v-for="model in models" 
                  :key="model.id" 
                  :value="model.model"
                >
                  {{ model.model }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label for="year">Year</label>
              <input 
                id="year" 
                type="number" 
                v-model="formFields.year" 
                placeholder="e.g. 2018"
                min="1900"
                :max="new Date().getFullYear() + 1"
                class="form-control"
              />
            </div>

            <div class="form-group">
              <label for="mileage">Mileage (km)</label>
              <input 
                id="mileage" 
                type="number" 
                v-model="formFields.mileage" 
                placeholder="e.g. 120000"
                min="0"
                class="form-control"
              />
            </div>

            <div class="form-group">
              <label for="fuel_type">Fuel Type</label>
              <select 
                id="fuel_type" 
                v-model="formFields.fuel_type"
                class="form-control"
              >
                <option value="" disabled selected>Select fuel type</option>
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="Electric">Electric</option>
                <option value="Hybrid">Hybrid</option>
              </select>
            </div>

            <div class="form-group">
              <label for="transmission">Transmission</label>
              <select 
                id="transmission" 
                v-model="formFields.transmission"
                class="form-control"
              >
                <option value="" disabled selected>Select transmission</option>
                <option value="Manual">Manual</option>
                <option value="Automatic">Automatic</option>
                <option value="CVT">CVT</option>
              </select>
            </div>

            <div class="form-group">
              <label for="engine_size">Engine Size (L)</label>
              <input 
                id="engine_size" 
                type="number" 
                step="0.1" 
                v-model="formFields.engine_size" 
                placeholder="e.g. 2.0"
                min="0.5"
                max="10"
                class="form-control"
              />
            </div>

            <div class="form-group">
              <label for="body_type">Body Type</label>
              <select 
                id="body_type" 
                v-model="formFields.body_type"
                class="form-control"
              >
                <option value="" disabled selected>Select body type</option>
                <option value="Sedan">Sedan</option>
                <option value="Hatchback">Hatchback</option>
                <option value="SUV">SUV</option>
                <option value="Coupe">Coupe</option>
                <option value="Convertible">Convertible</option>
                <option value="Van">Van</option>
                <option value="Pickup">Pickup</option>
              </select>
            </div>

            <div class="form-group">
              <label for="color">Color</label>
              <input 
                id="color" 
                type="text" 
                v-model="formFields.color" 
                placeholder="e.g. Black, Red"
                class="form-control"
              />
            </div>
          </div>
        </section>
      </div>

      <div class="form-footer">
        <div v-if="error" class="error-message">
          {{ error }}
        </div>
        
        <button 
          type="submit" 
          class="submit-button"
          :disabled="isLoading"
        >
          <span v-if="isLoading">Processing...</span>
          <span v-else>Submit Vehicle</span>
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.form-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.form-title {
  text-align: center;
  margin-bottom: 2rem;
  color: #2c3e50;
  font-size: 2rem;
  font-weight: 600;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.form-sections {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

.image-section {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.image-upload-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.image-dropzone {
  border: 2px dashed #d1d5db;
  border-radius: 8px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  background-color: #f9fafb;
  min-height: 300px;
}

.image-dropzone:hover {
  border-color: #3b82f6;
  background-color: #f0f7ff;
}

.dropzone-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  text-align: center;
  color: #6b7280;
}

.upload-icon {
  width: 48px;
  height: 48px;
  fill: #9ca3af;
}

.hint-text {
  font-size: 0.875rem;
  color: #9ca3af;
}

.image-previews {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}

.image-preview {
  position: relative;
  border-radius: 6px;
  overflow: hidden;
  aspect-ratio: 4/3;
}

.preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.remove-image-btn {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background-color: rgba(239, 68, 68, 0.9);
  color: white;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1rem;
  line-height: 1;
  padding: 0;
}

.remove-image-btn:hover {
  background-color: rgba(220, 38, 38, 0.9);
}

.details-section {
  background-color: #fff;
  border-radius: 8px;
  padding: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.form-control {
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: all 0.2s ease;
  background-color: #fff;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1rem;
}

.form-footer {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 1rem;
}

.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  text-align: center;
}

.submit-button {
  padding: 0.875rem 2rem;
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
  max-width: 300px;
}

.submit-button:hover {
  background-color: #2563eb;
}

.submit-button:disabled {
  background-color: #bfdbfe;
  cursor: not-allowed;
}

@media (max-width: 1024px) {
  .form-sections {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .form-wrapper {
    padding: 1rem;
  }
}
</style>