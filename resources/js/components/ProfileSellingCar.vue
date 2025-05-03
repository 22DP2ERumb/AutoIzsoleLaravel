<script setup>
    import { ref, watch } from 'vue'
    import axios from 'axios'
    import ProfileCar from './ProfileCar.vue'

    const props = defineProps({
    carId: {
        type: [Number, String],
        required: true
    }
    })

    const car = ref(null)

    const fetchCar = async () => {
    try {
        const response = await axios.get(`/getCar/${props.carId}`)
        car.value = response.data
    } catch {
        car.value = null
    }
    }

    watch(() => props.carId, fetchCar, { immediate: true })
</script>

<template>
  <div class="SellCar-selection">
    <ProfileCar v-if="car" :car="car" />
    <div class="SellCarOptions">
        <button>List for Sale</button>
        <button>List for Auction</button>
    </div>
  </div>
</template>

<style>
    .SellCarOptions{
        margin: 50px;
    }
    .SellCarOptions button{
        width: 200px;
        height: 65px;
        margin-inline: 170px;
        border-radius: 15px;
        border: 3px solid #000;
    }
    .SellCar-selection{
        width: 100%;
        height: 100%;
        margin: 20px;
        margin-left: 60px;
        display: flex;
    }
    .SellCar-selection .ProfileCarcard{
        width: 30%;
        height: 90%;
    }
    .SellCar-selection .ProfileCarcard:hover{
        transform: scale(1.02);
    }
</style>