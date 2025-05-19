<script setup>
    import { ref } from 'vue';
    import axios from 'axios'

    const props = defineProps({
    car: Object
    }); 

    const currentImageIndex = ref(0);

    function nextImage() {
        if (props.car.images.length > 0) {
            currentImageIndex.value = (currentImageIndex.value + 1) % props.car.images.length;
        }
    }

    function prevImage() {
        if (props.car.images.length > 0) {
            currentImageIndex.value = (currentImageIndex.value - 1 + props.car.images.length) % props.car.images.length;
        }
    }

</script>

<template>
    <div class="ProfileCarcard" :class="{ 'inactiveCar': !car.auctions.is_active }">
        <RouterLink :to="`/auction/${car.id}`">
            <div class="image-container">
                <img class="car-img" :src="car.images[currentImageIndex]?.image_path"></img>

                <button v-if="car.images.length > 1" class="arrow left" @click.prevent.stop="prevImage">‹</button>
                <button v-if="car.images.length > 1" class="arrow right" @click.prevent.stop="nextImage">›</button>               
            </div>

            <div class="card-description">
                <h3 class="car-title">{{car.brand.manufacturer}} {{car.model.model}}</h3>
             
                <ul class="car-details">
                    <li>Year: {{ car.year }}</li>
                    <li>Mileage: {{ car.mileage }}km</li>
                    <br>
                    <li v-if="car.auctions.is_active" style="color: red;">End date: <span>{{ car.auctions.end_time }}</span></li>
                    <li v-if="!car.auctions.is_active" style="color: green;">Start date: <span>{{ car.auctions.start_time }}</span></li>
                    
                    <br>
                </ul>
            </div>
            

            <img class="car-logo" :src="car.brand.image_path">
        </RouterLink>
    </div>
</template>

<style>
.inactiveCar {
    opacity: 0.5; /* example: 50% transparent */
}
.arrow {
    position: absolute;
    top: 25%;
    color: white;
    font-size: 3rem;
    cursor: pointer;
    opacity: 0;
    margin: 5px;
    border-radius: 4px;
}

.ProfileCarcard:hover .arrow {
    opacity: 1;
}

.arrow.left {
    left: 10px;
}

.arrow.right {
    right: 10px;
}

.ProfileCarcard {
    position: relative;
    display: flex;
    margin: 20px;
    width: 250px;
    height: 350px;
    border-radius: 2px;
    box-shadow: 0 0 0 2px black;
    transition: transform 0.3s ease, color 0.3s ease;
}

.ProfileCarcard:hover {
    transform: scale(1.2);
}

.image-container {
    position: relative;
    height: 50%;
    width: 100%;
}

.ProfileCarcard .car-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    border-bottom: 2px solid black;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.ProfileCarcard .car-logo {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 45px;
    height: auto;
}

.ProfileCarcard .card-description {
    padding: 10px;
    text-align: center;
}

.ProfileCarcard .car-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 8px;
}

.ProfileCarcard .car-details {
    padding: 0;
    margin: 0;
    list-style: none;
    font-size: 0.9rem;
}

.ProfileCarcard .car-details li {
    margin: 4px 0;
    font-weight: bold;
}

</style>