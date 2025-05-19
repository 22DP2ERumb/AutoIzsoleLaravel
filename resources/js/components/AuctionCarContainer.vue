<script setup>
    import { ref, watch, onMounted } from 'vue'
    import axios from 'axios'
    import { useRouter } from 'vue-router';

const props = defineProps({
    carId: {
        type: [Number, String],
        required: true
    }
    })
</script>

<template>
    <div class="auctioncar-container">
        JOJOJOJO
    </div>
</template>

<style>
    .auctioncar-container{
        margin: 50px;
    }
</style>