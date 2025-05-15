<script setup>
    import { ref, watch, onMounted } from 'vue'
    import axios from 'axios'
    import ProfileCar from './ProfileCar.vue'
    import { useRouter } from 'vue-router';

    const router = useRouter();
    const props = defineProps({
    carId: {
        type: [Number, String],
        required: true
    }
    })

    const car = ref(null)
    const AuctionCar = ref(false)
    const editCar = ref(false)



    const fetchCar = async () => {
    try {
        const response = await axios.get(`/getCar/${props.carId}`)
        car.value = response.data
        newBrand.value = car.value.brand.manufacturer
        newModel.value = car.value.model.model
        newYear.value = car.value.year
        newMileage.value = car.value.mileage
        newFuelType.value = car.value.fuel_type
        newTransmission.value = car.value.transmission
        newEngineSize.value = car.value.engine_size
        newBodyType.value = car.value.body_type
        newColor.value = car.value.color


        const selectedBrand = response.data.brand;
        if (selectedBrand && selectedBrand.id) {
            getModels(selectedBrand.id);
        }

    } catch {
        car.value = null
        newBrand.value = ''
    }
    }

    watch(() => props.carId, fetchCar, { immediate: true })
    onMounted(async () => {
        
        try {
            const response = await axios.get('/allCarBrands')
            brands.value = response.data
        } catch (error) {
            console.error('Error fetching car brands:', error)
        }
    })

    const startingPrice = ref(0.0);
    const buyOutPrice = ref(0.0);
    const bidIncrement = ref(0.0);
    const reservePrice = ref(0.0);

    const startTime = ref(new Date().toLocaleDateString('sv-SE'));
    const endTime = ref(new Date().toLocaleDateString('sv-SE'));

    const error = ref('')

    const brands = ref([])

    const models = ref([])

    const getModels = async (brandId) => {
    try {
        const response = await axios.get(`/getModelsByBrand/${brandId}`)
        models.value = response.data
    } catch (error) {
        console.error('Error fetching car models:', error)
    }}



    const newBrand = ref();
    const newModel = ref();
    const newYear = ref();
    const newMileage = ref();
    const newFuelType = ref();
    const newTransmission = ref();
    const newEngineSize = ref();
    const newBodyType = ref();
    const newColor = ref();

    
    const EditCar = async () => {
    if (!validateNewValues()) return;

    try {
        // Make sure the values are passed correctly, especially for brand and model
        await axios.post('/updateCar', {
            car_id: car.value.id, // Ensure car_id is being sent
            car_brand: newBrand.value, // Ensure this value is correctly passed
            car_model: newModel.value, // Ensure this value is correctly passed
            year: newYear.value,
            mileage: newMileage.value,
            fuel_type: newFuelType.value,
            transmission: newTransmission.value,
            engine_size: newEngineSize.value.toString(), // Ensure engine size is passed as a string
            body_type: newBodyType.value,
            color: newColor.value,
        });

        // Reload the page to reflect the updated data
        window.location.reload(); // This will reload the current page

    } catch (error) {
        console.error("Error updating car:", error);
        // Optionally, handle the error to show a user-friendly message
    }
};

watch(newBrand, (newVal) => {
    const selectedBrand = brands.value.find(b => b.manufacturer === newVal);
    if (selectedBrand) {
        // Fetch models for the selected brand
        getModels(selectedBrand.id)
            .then(() => {
                // Reset newModel to null or to the first model (if desired)
                newModel.value = null; // Clears the previously selected model
            })
            .catch(error => {
                console.error('Error fetching models:', error);
            });
    }
});
        
        const validateNewValues = () => {
        if (!newBrand.value || !newModel.value) {
            error.value = 'Brand and Model are required';
            return false;
        }
        if (!newYear.value || newYear.value < 1886 || newYear.value > new Date().getFullYear() + 1) {
            error.value = 'Invalid year';
            return false;
        }
        if (newMileage.value < 0) {
            error.value = 'Mileage cannot be negative';
            return false;
        }
        if (!newFuelType.value) {
            error.value = 'Fuel type is required';
            return false;
        }
        if (!newTransmission.value) {
            error.value = 'Transmission is required';
            return false;
        }
        if (newEngineSize.value < 0) {
            error.value = 'Engine size cannot be negative';
            return false;
        }
        if (!newBodyType.value || !newColor.value) {
            error.value = 'Body type and color are required';
            return false;
        }

        error.value = '';
        return true;
    }




    const StartAuction = async () => {
        if (!validateAuction()) return


        try {
            
            await axios.post('/AuctionCar', {
            car_id: car.value.id,
            startingPrice: startingPrice.value,
            buyOutPrice: buyOutPrice.value,
            bidIncrement: bidIncrement.value,
            reservePrice: reservePrice.value,

            startTime: startTime.value,
            endTime: endTime.value,

            });

            startingPrice.value = 0.0;
            buyOutPrice.value = 0.0;
            bidIncrement.value = 0.0;
            reservePrice.value = 0.0;

            startTime.value = new Date().toLocaleDateString('sv-SE');
            endTime.value = new Date().toLocaleDateString('sv-SE');


            router.push({ path: '/profile'});
        } catch (error) {
            console.error(error);
        }
    };
    const validateAuction = () => {
        if (!car.value.id) {
            error.value = 'CarID is required'
            return false
        }
        if (startingPrice.value < 0) {
            error.value = 'Starting price cant be lower than 0'
            return false
        }
        if (buyOutPrice.value < 0) {
            error.value = 'Buy Out price cant be lower than 0'
            return false
        }
        if (buyOutPrice.value < startingPrice.value) {
            error.value = 'Buy Out price cant be lower than starting price'
            return false
        }
        if (bidIncrement.value < 0) {
            error.value = 'Bid Increment cant be lower than 0'
            return false
        }
        if (reservePrice.value < 0) {
            error.value = 'Reserve Price cant be lower than 0'
            return false
        }
        if (reservePrice.value < startingPrice.value) {
            error.value = 'Reserve Price cant be lower than Starting Price'
            return false
        }
        if (reservePrice.value > buyOutPrice.value) {
            error.value = 'Reserve Price cant be higher than Buy Out Price'
            return false
        }
        if (!startTime.value || !endTime.value) {
            error.value = 'Start and End dates are required';
            return false;
        }

        if (new Date(startTime.value) < new Date().toLocaleDateString('sv-SE')) {
            error.value = 'Start time cannot be in the past';
            return false;
        }

        if (new Date(endTime.value) <= new Date(startTime.value)) {
            error.value = 'End time must be after start time';
            return false;
        }
        

        error.value = ''
        return true
        }
</script>

<template>
  <div class="SellCar-selection">
    <ProfileCar v-if="car" :car="car" @click="AuctionCar = false; editCar = false" />

    
    

    <div class="SellCar-specifications" v-if="!AuctionCar && !editCar">
        <div class="car-specifications">
            <ul>
                <i class="pi pi-cog editcarcog" v-if="!AuctionCar" @click="editCar=true"></i>
                <li>Brand: <span>{{ car?.brand.manufacturer }}</span> </li>
                <li>Model: <span>{{ car?.model.model }}</span> </li>
                <li>Year: <span>{{ car?.year }}</span> </li>
                <li>Mileage: <span>{{ car?.mileage }}</span> </li>
                <li>Fuel Type: <span>{{ car?.fuel_type }}</span> </li>
                <li>Transmission: <span>{{ car?.transmission }}</span> </li>
                <li>Engine Size: <span>{{ car?.engine_size }}</span> </li>
                <li>Body Type: <span>{{ car?.body_type }}</span> </li>
                <li>Color: <span>{{ car?.color }}</span> </li>
                
            </ul>
        </div>
        <div class="SellCarOptions">
            <button>List for Sale</button>
            <button @click="AuctionCar = true">List for Auction</button>
        </div>
    </div>

    <div class="editCar-specifications" v-if="editCar && !AuctionCar">
  <div class="car-specifications">
    <ul>
        <li>
            Brand:
            <select v-model="newBrand">
            <option disabled value="">{{ car?.brand.manufacturer }}</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.manufacturer">
                {{ brand.manufacturer }}
            </option>
            </select>
        </li>

        <li>
            Model:
            <select v-model="newModel">
            <option disabled value="">{{ car?.model.model }}</option>
            <option v-for="model in models" :key="model.id" :value="model.model">
                {{ model.model }}
            </option>
            </select>
        </li>

        <li>Year: <input type="number" v-model="newYear" /></li>
        <li>Mileage: <input type="number" v-model="newMileage" /></li>

        <li>
            Fuel Type:
            <select v-model="newFuelType">
            <option disabled value="">{{ car?.fuel_type }}</option>
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
            <option value="Hybrid">Hybrid</option>
            </select>
        </li>

        <li>
            Transmission:
            <select v-model="newTransmission">
            <option disabled value="">{{ car?.transmission }}</option>
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
            <option value="CVT">CVT</option>
            </select>
        </li>

        <li>Engine Size: <input type="number" step="0.1" v-model="newEngineSize" /></li>
        <li>Body Type: <input type="text" v-model="newBodyType" /></li>
        <li>Color: <input type="text" v-model="newColor" /></li>

        <button @click="EditCar()">EDIT</button>
        <p v-if="error" class="auction-car-error-message">{{ error }}</p>
        </ul>
    </div>
</div>

    <div class="AuctionCar-specifications" v-if="AuctionCar && !editCar">
        <div class="car-specifications">
            <ul>
                <li>Starting Price: <input type="number" v-model="startingPrice"> </li>
                <li>Buy Out Price: <input type="number" v-model="buyOutPrice"> </li>
                <li>Bid Increment: <input type="number" v-model="bidIncrement"> </li>
                <li>Reserve Price: <input type="number" v-model="reservePrice"> </li>
                <li>Auction Start Time: <input type="date" v-model="startTime"> </li>
                <li>Auction End Time: <input type="date" v-model="endTime"> </li>
                <button @click="StartAuction()">Start Auction</button>
                <p v-if="error" class="auction-car-error-message">{{ error }}</p>
            </ul>
        </div>

    </div>
    
  </div>
</template>

<style>
    .editCar-specifications input,select{
        margin-left: 60px;
        margin-bottom: 5px;
        height: 25px;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
    }
    .editcarcog{
        font-size: 20px;
        cursor: pointer;
        margin-left: 380px;
    }
    .auction-car-error-message{
        color: red;
        font-size: 14px;
        margin: 4px 0 0 0;
    }
    .AuctionCar-specifications button{
        margin-top: 50px;
        border: green solid 2px;
        border-radius: 10px;
        width: 200px;
        height: 65px;
        transition: background-color 0.2s;
        font-weight: bold;
    }
    .AuctionCar-specifications button:hover{
        background-color: grey;
    }
    .car-specifications, .AuctionCar-specifications, .editCar-specifications {
        margin: 0 auto;
        text-align: center;
    }
    .AuctionCar-specifications input{
        margin-left: 60px;
        margin-bottom: 5px;
        height: 25px;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
    }
    .car-specifications ul {
        margin: 30px auto 0 auto;
        width: 400px;
    }
    .car-specifications li {
        position: relative;
        margin: 20px auto;
        border-bottom: 1px solid black;
        width: 400px;
        text-align: left;
        font-style: italic;
    }

    .car-specifications li {
    display: flex;
    justify-content: space-between;
    font-style: italic;
}

.car-specifications li span {
    position: static; /* remove absolute positioning */
    font-weight: bold;
    font-style: normal;
}
    .SellCarOptions{
        margin-top: 120px;
        display: flex;
    }
    .SellCarOptions button{
        width: 200px;
        height: 65px;
        margin-inline: 60px;
        border-radius: 15px;
        border: 2px solid green;
        transition: background-color 0.4s;
        font-weight: bold;
    }
    .SellCarOptions button:hover{
        background-color: grey;
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