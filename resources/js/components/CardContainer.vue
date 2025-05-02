<script setup>
    import CarCard from './CarCard.vue'
</script>
<template>
     <div class="card-container">

        <!-- massiv.map(item => {
            return (
                <CarCard item.src item/>
            )
        }) -->
        <CarCard/>
        <CarCard/>
        <CarCard/>
        <CarCard/>
    </div>
</template>
<style>
    .card-container{
        display: flex;
        justify-content: center;
        margin: 30px;
        flex-wrap: wrap;
    }
</style>