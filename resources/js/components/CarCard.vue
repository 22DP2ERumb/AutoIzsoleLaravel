<script setup>

</script>

<template>
    <div class="card">
        <RouterLink to="/car">
            <img class="car-img" src="/images/Car_example1.jpg">
                <div class="card-description">
                    <h3 class="car-title">Audi Q3</h3>
                    <p class="price">300000$</p>
                    <ul class="car-details">
                        <li>Year: 2022</li>
                        <li>Mileage: 25,000 km</li>
                    </ul>
                </div>

            <img class="car-logo" src="/images/Car_logo_example.png">
        </RouterLink>
    </div>
</template>
<style>
    .card{
    position: relative;
    display: flex;
    margin: 20px;
    width: 250px;
    height: 350px;
    border-radius: 2px;
    box-shadow: 0 0 0 2px black;
    transition: transform 0.3s ease, color 0.3s ease;
    }
    .card:hover{
        transform: scale(1.2);
    }
    .car-img{
        width: 100%;
        height: 50%;
        border-bottom: 2px solid black;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }
    .car-logo {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 45px;
        height: auto;
    }
    .card-description {
    padding: 10px;
    text-align: center;
    }
  
    .car-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 8px;
    }
  
    .car-details {
        padding: 0;
        margin: 0;
        list-style: none;
        font-size: 0.9rem;
    }
    
    .car-details li {
        margin: 4px 0;
        font-weight: bold;
    }
    .price{
    position: absolute;
    bottom: -10px;
    left: 10px;
    background-color: green;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;

    }
</style>