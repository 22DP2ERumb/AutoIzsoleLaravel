<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

const route = useRoute();
const carId = ref(route.params.carid);
const cardata = ref(null);
const currentImageIndex = ref(0);
const chooseInfo = ref('auction');
const yourBid = ref(0);
const userBid = ref(0);

const fetchUserBid = async () => {
  try {
    const response = await axios.get('/getUserBid', {
      params: { auction_id: carId.value }
    });
    userBid.value = response.data.highest_bid ?? 0;
  } catch (error) {
    console.error('Failed to fetch user bid:', error);
    userBid.value = 0;
  }
};

const fetchCar = async () => {
  if (!carId.value) return;
  try {
    const response = await axios.get(`/getAuctionCar/${carId.value}`);
    cardata.value = response.data;
    currentImageIndex.value = 0;
  } catch (error) {
    console.error('Failed to fetch auction car:', error);
    cardata.value = null;
  }
};

const bidStats = ref({
  total_bids: 0,
  highest_bid: 0,
  unique_bidders: 0,
});

const fetchBidStats = async () => {
  try {
    const response = await axios.get('/getBidData', {
      params: { auction_id: carId.value }
    });
    bidStats.value = response.data;
    yourBid.value = response.data.highest_bid; // set initial bid here
  } catch (error) {
    console.error('Failed to fetch bid stats:', error);
  }
};
const bidIncrements = computed(() => {
  const base = cardata.value?.auctions?.bid_increment || 10;
  return [base, base * 2, base * 3];
});

onMounted(() => {
  fetchCar();
  fetchBidStats();
  fetchUserBid(); // new
});

watch(() => route.params.carid, (newCarId) => {
  carId.value = newCarId;
  fetchCar();
  fetchBidStats();
  fetchUserBid(); // new
});

function setInfo(type) {
  chooseInfo.value = type;
}

function nextImage() {
  const images = cardata.value?.images || [];
  if (images.length > 0) {
    currentImageIndex.value = (currentImageIndex.value + 1) % images.length;
  }
}

function prevImage() {
  const images = cardata.value?.images || [];
  if (images.length > 0) {
    currentImageIndex.value = (currentImageIndex.value - 1 + images.length) % images.length;
  }
}
const submitBid = async () => {
  try {
    const response = await axios.post('/createBid', {
      auction_id: carId.value,
      bid_amount: yourBid.value
    });
    alert('Bid submitted successfully!');
    console.log(response.data);

    await fetchBidStats();
    await fetchUserBid();
  } catch (error) {
    console.error('Failed to submit bid:', error);
    alert(error.response?.data?.message || 'Failed to submit bid.');
  }
};

function increaseBid(amount) {
  yourBid.value += amount;
}
</script>


<template>
  <div class="auction-main" v-if="cardata && cardata.images?.length">
    <div class="auction-left">
      <div class="auction-image-container">
        <img :src="cardata.images[currentImageIndex]?.url" alt="Car Image" class="car-img" />
        <button v-if="cardata.images.length > 1" class="arrow left" @click="prevImage">‹</button>
        <button v-if="cardata.images.length > 1" class="arrow right" @click="nextImage">›</button>
      </div>
      <h2 v-if="cardata.auctions.is_active" style="color: green;">End date: {{ cardata.auctions.end_time }}</h2>
      <h2 v-if="!cardata.auctions.is_active" style="color: red;">Start date: {{ cardata.auctions.start_time }}</h2>

      <div class="seller-data">
        <span><strong>Name:</strong> Seller Name</span>
        <span><strong>Review:</strong> ★★★★☆</span>
      </div>
    </div>

    <div class="auction-right">
      <div class="auction-options">
        <button @click="setInfo('auction')"><i class="pi pi-dollar"></i></button>
        <button @click="setInfo('carinfo')"><i class="pi pi-info"></i></button>
        <button @click="setInfo('id-card')"><i class="pi pi-id-card"></i></button>
      </div>

      <div class="auction-right-info">
        <div v-if="chooseInfo === 'auction'" class="auction-bid-data">
          <h3>Bid Info</h3>
          <div class="bid-info">
            <p>Highest Bid: <strong>€{{ bidStats.highest_bid }}</strong></p>
            <p>Total Bids: <strong>{{ bidStats.total_bids }}</strong></p>
            <p>Unique Bidders: <strong>{{ bidStats.unique_bidders }}</strong></p>
            <p>Your Bid: <strong>€{{ userBid }}</strong></p>
          </div>

          <h3>Place a Bid</h3>
          <div class="make-bid-info">
            <div class="your-bid">
              <label>Your Bid:</label>
              <input type="number" :value="yourBid" readonly />
            </div>
            <div class="bid-value">
              <button v-for="inc in bidIncrements" :key="inc" @click="increaseBid(inc)">
                +{{ inc }}
              </button>
            </div>
            <button class="submit-bid-btn" @click="submitBid">Submit Bid</button>
          </div>
        </div>
        <div v-if="chooseInfo === 'carinfo'" class="car-info-data">
          <h3>Car Details</h3>
          <ul>
            <li><strong>Brand:</strong> {{ cardata.brand?.manufacturer }}</li>
            <li><strong>Model:</strong> {{ cardata.model?.model }}</li>
            <li><strong>Year:</strong> {{ cardata.year }}</li>
            <li><strong>Mileage:</strong> {{ cardata.mileage.toLocaleString() }} km</li>
            <li><strong>Fuel Type:</strong> {{ cardata.fuel_type }}</li>
            <li><strong>Transmission:</strong> {{ cardata.transmission }}</li>
            <li><strong>Engine Size:</strong> {{ cardata.engine_size }} L</li>
            <li><strong>Body Type:</strong> {{ cardata.body_type }}</li>
            <li><strong>Color:</strong> {{ cardata.color }}</li>
          </ul>
          <div class="comment-section">
            <h4>Comments</h4>
            <p>This car has been maintained regularly and runs smoothly. Ideal for city driving and long trips alike. More details to be added later.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<style>
.comment-section {
  margin-top: 20px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 15px;
  font-size: 0.95rem;
  color: #333;
}
.car-info-data {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.car-info-data h3 {
  margin-bottom: 15px;
}

.car-info-data ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.car-info-data li {
  margin-bottom: 8px;
  font-size: 1rem;
}
.auction-main .submit-bid-btn {
  margin-top: 15px;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.2s;
}

.auction-main .submit-bid-btn:hover {
  background-color: #0056b3;
}
  .auction-main {
  display: flex;
  gap: 40px;
  padding: 20px;
}

.auction-main .auction-left {
  flex: 1;
}

.auction-main .auction-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.auction-image-container {
  position: relative;
  width: 100%;
  max-width: 500px;
  height: 300px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.car-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.auction-main .arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 2rem;
  color: white;
  background-color: rgba(0,0,0,0.5);
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 5px;
}

.arrow.left { left: 10px; }
.arrow.right { right: 10px; }

.seller-data {
  margin-top: 15px;
  font-size: 1.1rem;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.auction-options {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.auction-options button {
  padding: 10px;
  border: 1px solid #888;
  border-radius: 5px;
  background-color: white;
  cursor: pointer;
}

.auction-options button:hover {
  background-color: #f0f0f0;
}

.auction-right-info {
  width: 100%;
  max-width: 400px;
}

.auction-bid-data {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.bid-info p {
  margin: 8px 0;
  font-size: 1rem;
}

.make-bid-info {
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.your-bid {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}

.your-bid input {
  width: 100px;
  padding: 8px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #fff;
}

.bid-value {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.bid-value button {
  padding: 10px 15px;
  border: 1px solid #444;
  background-color: #eee;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.bid-value button:hover {
  background-color: #ccc;
}

</style>