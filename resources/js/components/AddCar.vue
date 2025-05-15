<script setup>
import { ref, watch, onMounted } from 'vue'
  import axios from 'axios'
  import ProfileCar from './ProfileCar.vue'
  import { useRouter } from 'vue-router';

const router = useRouter()
const images = ref([])
const fileInput = ref(null)

const carBrand = ref('')
const carModel = ref('')
const carYear = ref('')
const carMileage = ref('')
const carFuel = ref('')
const carTransmission = ref('')
const carEngineSize = ref('')
const carBodyType = ref('')
const carColor = ref('')
const error = ref('')
const brands = ref([])



watch(carBrand, (newVal) => {
    const selectedBrand = brands.value.find(b => b.manufacturer === newVal);
    if (selectedBrand) {
        // Fetch models for the selected brand
        getModels(selectedBrand.id)
            .then(() => {
                // Reset newModel to null or to the first model (if desired)
                carModel.value = null; // Clears the previously selected model
            })
            .catch(error => {
                console.error('Error fetching models:', error);
            });
    }
});

const models = ref([])

const getModels = async (brandId) => {
    try {
        const response = await axios.get(`/getModelsByBrand/${brandId}`)
        models.value = response.data
    } catch (error) {
        console.error('Error fetching car models:', error)
    }}

onMounted(async () => {
  try {
    const response = await axios.get('/allCarBrands')
    brands.value = response.data
  } catch (error) {
    console.error('Error fetching car brands:', error)
  }
})

const triggerUpload = () => {
  fileInput.value.click()
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
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

const submitForm = async () => {
  if (!validateForm()) return
  const formData = new FormData()

  // Append all form data
  formData.append('brand', carBrand.value)
  formData.append('model', carModel.value)
  formData.append('year', carYear.value)
  formData.append('mileage', carMileage.value)
  formData.append('fuel_type', carFuel.value)
  formData.append('transmission', carTransmission.value)
  formData.append('engine_size', carEngineSize.value)
  formData.append('body_type', carBodyType.value)
  formData.append('color', carColor.value)

  // Append images
  images.value.forEach((imgObj, index) => {
    formData.append(`images[${index}]`, imgObj.file)
  })

  try {
    const response = await axios.post('/addCar', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    router.push('/profile');
  } catch (error) {
    console.error('Error:', error)
  }
}


const validateForm = () => {
  if (!carBrand.value) {
    error.value = 'Brand is required'
    return false
  }
  if (!carModel.value) {
    error.value = 'Model is required'
    return false
  }
  if (!carYear.value || isNaN(carYear.value)) {
    error.value = 'Valid year is required'
    return false
  }
  if (!carMileage.value || isNaN(carMileage.value)) {
    error.value = 'Valid mileage is required'
    return false
  }
  if (!carFuel.value) {
    error.value = 'Fuel type is required'
    return false
  }
  if (!carTransmission.value) {
    error.value = 'Transmission is required'
    return false
  }
  if (!carEngineSize.value || isNaN(carEngineSize.value)) {
    error.value = 'Valid engine size is required'
    return false
  }
  if (!carBodyType.value) {
    error.value = 'Body type is required'
    return false
  }
  if (!carColor.value) {
    error.value = 'Color is required'
    return false
  }

  error.value = ''
  return true
}
</script>

<template>
    <form @submit.prevent="submitForm" class="form-container">
      <div class="left-section">
        <div class="image-box" @click="triggerUpload">
          <input
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            ref="fileInput"
            class="file-input"
          />
          <button type="button" class="add-button">+</button>
        </div>
  
        <div v-if="images.length" class="preview-box">
          <div v-for="(img, index) in images" :key="index" class="preview-image">
            <img :src="img.preview" />
          </div>
        </div>
      </div>
  
      <div class="right-section">
        <label>
            Brand:
            <select v-model="carBrand">
            <option value="" disabled>Select brand</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.manufacturer">
              {{ brand.manufacturer }}
            </option>
            </select>
        </label>

        <label>
            Model:
            <select v-model="carModel">
            <option value="" disabled>Select brand</option>
            <option v-for="model in models" :key="model.id" :value="model.model">
                {{ model.model }}
            </option>
            </select>
        </label>

        <label>
            Year:
            <input type="number" v-model="carYear" placeholder="e.g. 2018" />
        </label>

        <label>
            Mileage (km):
            <input type="number" v-model="carMileage" placeholder="e.g. 120000" />
        </label>

        <label>
            Fuel Type:
            <select v-model="carFuel">
            <option value="">Select fuel type</option>
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
            <option value="Hybrid">Hybrid</option>
            </select>
        </label>

        <label>
            Transmission:
            <select v-model="carTransmission">
            <option value="">Select transmission</option>
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
            <option value="CVT">CVT</option>
            </select>
        </label>

        <label>
            Engine Size (L):
            <input type="number" step="0.1" v-model="carEngineSize" placeholder="e.g. 2.0" />
        </label>

        <label>
            Body Type:
            <select v-model="carBodyType">
            <option value="">Select body type</option>
            <option value="Sedan">Sedan</option>
            <option value="Hatchback">Hatchback</option>
            <option value="SUV">SUV</option>
            <option value="Coupe">Coupe</option>
            <option value="Convertible">Convertible</option>
            <option value="Van">Van</option>
            <option value="Pickup">Pickup</option>
            </select>
        </label>

        <label>
            Color:
            <input type="text" v-model="carColor" placeholder="e.g. Black, Red" />
        </label>
        <p v-if="error" class="carAdd-error-message">{{ error }}</p>

        </div>

  
      <button type="submit" class="submit-button">Submit All</button>
    </form>
  </template>
  
  
  <style scoped>
  .carAdd-error-message{
    color: red;
    font-size: 14px;
    margin: 4px 0 0 0;
  }
  .form-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 100%;
  max-width: 100%;
  padding: 0 40px;
  box-sizing: border-box;
}
  
  .left-section {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  
  .right-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-items: center;
    
  }
  
  .image-box {
    width: 400px;
    height: 400px;
    border: 2px dashed #ccc;
    border-radius: 8px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin-bottom: 10px;
    flex-direction: column;
  }
  
  .file-input {
    display: none;
  }
  
  .add-button {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: #4caf50;
    color: white;
    font-size: 24px;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: background-color 0.2s;
  }
  
  .add-button:hover {
    background-color: #43a047;
  }
  
  .preview-box {
    max-width: 400px;
    max-height: 100px;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 6px;
    box-sizing: border-box;
    margin-bottom: 20px;
  }
  
  .preview-image {
    display: inline-block;
    margin-right: 8px;
  }
  
  .preview-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ccc;
  }
  
  .submit-button {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #2196f3;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  
  .submit-button:hover {
    background-color: #1976d2;
  }
  
  .right-section select,
  .right-section input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 200px;
  }
  </style>
  