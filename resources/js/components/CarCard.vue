<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
    car: Object
})

const router = useRouter()
const currentImageIndex = ref(0)
const isHovered = ref(false)

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

function navigateToAuction() {
    router.push(`/auction/${props.car.id}`)
}
</script>

<template>
    <div 
        class="car-card"
        :class="{ 'inactive': !car.auctions.is_active }"
        @click="navigateToAuction"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <div class="image-container">
            <img 
                class="car-image" 
                :src="car.images[currentImageIndex]?.image_path || '/placeholder-car.jpg'"
                :alt="`${car.brand.manufacturer} ${car.model.model}`"
            >
            
            <div v-if="car.images.length > 1" class="image-nav">
                <button class="nav-button prev" @click.stop="prevImage">
                    <i class="pi pi-chevron-left"></i>
                </button>
                <button class="nav-button next" @click.stop="nextImage">
                    <i class="pi pi-chevron-right"></i>
                </button>
            </div>
            
            <div class="image-counter" v-if="car.images.length > 1">
                {{ currentImageIndex + 1 }}/{{ car.images.length }}
            </div>
        </div>

        <div class="card-content">
            <div class="car-header">
                <h3 class="car-title">{{ car.brand.manufacturer }} {{ car.model.model }}</h3>
                <span class="car-year">{{ car.year }}</span>
            </div>
            
            <div class="car-details">
                <div class="detail-item">
                    <i class="pi pi-tachometer-alt"></i>
                    <span>{{ car.mileage.toLocaleString() }} km</span>
                </div>
                
                <div class="auction-status" :class="{ 'active': car.auctions.is_active }">
                    <i class="pi" :class="car.auctions.is_active ? 'pi-clock' : 'pi-calendar'"></i>
                    <span>{{ car.auctions.is_active ? 'Ends' : 'Starts' }}: {{ car.auctions.is_active ? car.auctions.end_time : car.auctions.start_time }}</span>
                </div>
            </div>
        </div>

        <div class="brand-logo">
            <img :src="car.brand.image_path" :alt="`${car.brand.manufacturer} logo`">
        </div>
    </div>
</template>

<style scoped>
.car-card {
    position: relative;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.car-card.inactive {
    opacity: 0.7;
}

.image-container {
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

.car-card:hover .car-image {
    transform: scale(1.03);
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

.car-card:hover .image-nav {
    opacity: 1;
}

.nav-button {
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

.nav-button:hover {
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

.card-content {
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
}

.car-year {
    font-size: 14px;
    color: #666;
    background: #f5f5f5;
    padding: 2px 8px;
    border-radius: 4px;
}

.car-details {
    margin-top: auto;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    margin-bottom: 8px;
}

.auction-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    padding: 6px 8px;
    border-radius: 4px;
    margin-top: 8px;
}

.auction-status.active {
    background: rgba(76, 201, 240, 0.1);
    color: #4cc9f0;
}

.auction-status:not(.active) {
    background: rgba(247, 37, 133, 0.1);
    color: #f72585;
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
    .image-nav {
        opacity: 1;
    }
    
    .car-card {
        max-width: 100%;
    }
}
</style>