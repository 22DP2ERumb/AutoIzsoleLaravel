<script setup>
import AuctionCar from './AuctionCar.vue'
import { useRoute } from 'vue-router'
import { ref, onMounted, watch, computed } from 'vue'

const route = useRoute()
const carId = ref(route.params.carid)
</script>

<template>
  <div class="auction-container">
    <AuctionCar :car-id="carId" />
  </div>
</template>

<style scoped>
.auction-container {
  max-width: 1200px;
  margin: 30px auto;
  padding: 0 20px;
}

@media (max-width: 768px) {
  .auction-container {
    margin: 20px 10px;
    padding: 0;
  }
}
</style>