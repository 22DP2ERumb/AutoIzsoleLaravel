<script setup>
import { ref } from 'vue';
import axios from 'axios'
import { useRouter } from 'vue-router'

const props = defineProps({
    car: Object
});

const router = useRouter()
const currentImageIndex = ref(0)
const showDropdown = ref(false)

function nextImage(e) {
    e.stopPropagation()
    if (props.car.images.length > 0) {
        currentImageIndex.value = (currentImageIndex.value + 1) % props.car.images.length
    }
}

function prevImage(e) {
    e.stopPropagation()
    if (props.car.images.length > 0) {
        currentImageIndex.value = (currentImageIndex.value - 1 + props.car.images.length) % props.car.images.length
    }
}

async function deleteCar(carId) {
    if (!confirm('Are you sure you want to delete this car?')) return
    
    try {
        await axios.delete(`/deleteCar/${carId}`)
        window.location.reload()
    } catch (error) {
        console.error('Error deleting car:', error)
        alert('Failed to delete the car.')
    }
}
function navigateToCar() {
  router.push(`/profile/${props.car.id}`)
}
</script>

<template>
    <div class="profile-car-card">
        <div class="car-image-container" @click="navigateToCar">
            <img 
                class="car-image" 
                :src="car.images[currentImageIndex]?.image_path || '/placeholder-car.jpg'"
                :alt="`${car.brand.manufacturer} ${car.model.model}`"
            >
            
            <div class="car-actions">
                <button class="action-btn delete-btn" @click.stop="deleteCar(car.id)">
                    <i class="pi pi-trash"></i>
                </button>
            </div>

            <div v-if="car.images.length > 1" class="image-nav">
                <button class="nav-btn prev" @click.stop="prevImage">
                    <i class="pi pi-chevron-left"></i>
                </button>
                <button class="nav-btn next" @click.stop="nextImage">
                    <i class="pi pi-chevron-right"></i>
                </button>
            </div>
            
            <div v-if="car.images.length > 1" class="image-counter">
                {{ currentImageIndex + 1 }}/{{ car.images.length }}
            </div>
        </div>

        <div class="car-details">
            <div class="car-header">
                <h3 class="car-title">{{ car.brand.manufacturer }} {{ car.model.model }}</h3>
                <span class="car-year">{{ car.year }}</span>
            </div>
            
            <div class="car-status" :class="{ 'active': car.auctions?.is_active }">
                <span v-if="car.auctions?.is_active" class="status-badge active">
                    <i class="pi pi-clock"></i> Ends: {{ car.auctions.end_time }}
                </span>
                <span v-else-if="car.auctions" class="status-badge inactive">
                    <i class="pi pi-calendar"></i> Starts: {{ car.auctions.start_time }}
                </span>
                <span v-else class="status-badge">
                    <i class="pi pi-car"></i> Not Listed
                </span>
            </div>
            
            <div class="car-specs">
                <div class="spec-item">
                    <i class="pi pi-tachometer-alt"></i>
                    <span>{{ car.mileage.toLocaleString() }} km</span>
                </div>
            </div>
        </div>

        <div class="brand-logo">
            <img :src="car.brand.image_path" :alt="`${car.brand.manufacturer} logo`">
        </div>
    </div>
</template>

<style scoped>
.profile-car-card {
    position: relative;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    cursor: pointer;
    width: 280px;
    height: 380px;
    display: flex;
    flex-direction: column;
}

.profile-car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.car-image-container {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.car-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.profile-car-card:hover .car-image {
    transform: scale(1.03);
}

.car-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 8px;
    z-index: 2;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: rgba(0, 0, 0, 0.7);
    transform: scale(1.1);
}

.delete-btn:hover {
    background: rgba(220, 53, 69, 0.8);
}

.image-nav {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 10px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.profile-car-card:hover .image-nav {
    opacity: 1;
}

.nav-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.nav-btn:hover {
    background: rgba(0, 0, 0, 0.7);
    transform: scale(1.1);
}

.image-counter {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
}

.car-details {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.car-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.car-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 70%;
}

.car-year {
    font-size: 14px;
    color: #666;
    background: #f5f5f5;
    padding: 2px 8px;
    border-radius: 4px;
}

.car-status {
    margin-bottom: 12px;
}

.status-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-badge.inactive {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.car-specs {
    margin-top: auto;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
}

.brand-logo {
    position: absolute;
    bottom: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 5px;
}

.brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

@media (max-width: 768px) {
    .profile-car-card {
        width: 100%;
        max-width: 300px;
    }
    
    .image-nav {
        opacity: 1;
    }
}
</style>