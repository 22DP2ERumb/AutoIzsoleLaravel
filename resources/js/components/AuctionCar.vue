<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const props = defineProps({
  carId: {
    type: [String, Number],
    required: true
  }
})

// Data
const carData = ref(null)
const currentImageIndex = ref(0)
const activeTab = ref('auction')
const yourBid = ref(0)
const userBid = ref(0)
const bidStats = ref({
  total_bids: 0,
  highest_bid: 0,
  unique_bidders: 0
})
const comments = ref([])
const hasCommented = ref(false)
const commentForm = ref({
  rating: 5,
  comment: ''
})

// Computed
const bidIncrements = computed(() => {
  const base = carData.value?.auctions?.bid_increment || 10
  return [base, base * 2, base * 3]
})

// Methods
const fetchCar = async () => {
  try {
    const response = await axios.get(`/getAuctionCar/${props.carId}`)
    carData.value = {
      ...response.data.car,
      averageRating: response.data.averageRating
    }
  } catch (error) {
    console.error('Failed to fetch car:', error)
  }
}

const fetchBidStats = async () => {
  try {
    const response = await axios.get('/getBidData', {
      params: { auction_id: props.carId }
    })
    bidStats.value = response.data
    yourBid.value = response.data.highest_bid
  } catch (error) {
    console.error('Failed to fetch bid stats:', error)
  }
}

const fetchUserBid = async () => {
  try {
    const response = await axios.get('/getUserBid', {
      params: { auction_id: props.carId }
    })
    userBid.value = response.data.highest_bid ?? 0
  } catch (error) {
    console.error('Failed to fetch user bid:', error)
  }
}

const fetchComments = async () => {
  try {
    const response = await axios.get(`/comments/${props.carId}`)
    comments.value = response.data.comments
    hasCommented.value = response.data.hasCommented
  } catch (error) {
    console.error('Failed to fetch comments:', error)
  }
}

const submitBid = async () => {
  try {
    await axios.post('/createBid', {
      auction_id: props.carId,
      bid_amount: yourBid.value
    })
    await Promise.all([fetchBidStats(), fetchUserBid()])
  } catch (error) {
    console.error('Bid submission failed:', error)
  }
}

const submitComment = async () => {
  try {
    const response = await axios.post('/auction/comment', {
      auction_id: carData.value.auctions.id,
      rating: commentForm.value.rating,
      comment: commentForm.value.comment.trim() // Add trim() to remove whitespace
    }, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });
    
    if (response.data.success) {
      alert('Comment submitted successfully!');
      commentForm.value.comment = '';
      await fetchComments();
      hasCommented.value = true;
    } else {
      throw new Error(response.data.message || 'Failed to submit comment');
    }
  } catch (error) {
    console.error('Comment submission failed:', error);
    alert(error.response?.data?.message || 
         error.message || 
         'Failed to submit comment. Please try again.');
  }
};

const nextImage = () => {
  if (carData.value?.images?.length) {
    currentImageIndex.value = (currentImageIndex.value + 1) % carData.value.images.length
  }
}

const prevImage = () => {
  if (carData.value?.images?.length) {
    currentImageIndex.value = (currentImageIndex.value - 1 + carData.value.images.length) % carData.value.images.length
  }
}

const increaseBid = (amount) => {
  yourBid.value += amount
}

const renderStars = (rating) => {
  const fullStars = '★'.repeat(Math.floor(rating))
  const halfStar = rating % 1 >= 0.5 ? '½' : ''
  const emptyStars = '☆'.repeat(5 - Math.ceil(rating))
  return `${fullStars}${halfStar}${emptyStars}`
}

// Lifecycle
onMounted(async () => {
  await Promise.all([
    fetchCar(),
    fetchBidStats(),
    fetchUserBid(),
    fetchComments()
  ])
})

watch(() => props.carId, () => {
  Promise.all([
    fetchCar(),
    fetchBidStats(),
    fetchUserBid(),
    fetchComments()
  ])
})
</script>

<template>
  <div v-if="carData" class="auction-car">
    <!-- Car Images Section -->
    <div class="car-images">
      <div class="image-container">
        <img 
          :src="carData.images[currentImageIndex]?.url" 
          :alt="`${carData.brand?.manufacturer} ${carData.model?.model}`"
          class="main-image"
        />
        <button 
          v-if="carData.images.length > 1" 
          class="nav-arrow prev"
          @click="prevImage"
        >
          ‹
        </button>
        <button 
          v-if="carData.images.length > 1" 
          class="nav-arrow next"
          @click="nextImage"
        >
          ›
        </button>
      </div>
      
      <div class="auction-status">
        <h3 v-if="carData.auctions?.is_active" class="active">
          <i class="pi pi-clock"></i> Ends: {{ carData.auctions.end_time }}
        </h3>
        <h3 v-else class="inactive">
          <i class="pi pi-calendar"></i> Starts: {{ carData.auctions?.start_time }}
        </h3>
      </div>

      <div v-if="carData.user" class="seller-info">
        <h4>Seller Information</h4>
        <p><strong>Name:</strong> {{ carData.user.name }}</p>
        <p>
          <strong>Rating:</strong> 
          <span v-html="renderStars(carData.averageRating)"></span>
          ({{ carData.averageRating?.toFixed(1) || '0' }})
        </p>
      </div>
    </div>

    <!-- Auction Info Section -->
    <div class="auction-info">
      <div class="tabs">
        <button 
          :class="{ active: activeTab === 'auction' }"
          @click="activeTab = 'auction'"
        >
          <i class="pi pi-dollar"></i> Auction
        </button>
        <button 
          :class="{ active: activeTab === 'details' }"
          @click="activeTab = 'details'"
        >
          <i class="pi pi-info-circle"></i> Details
        </button>
        <button 
          :class="{ active: activeTab === 'comments' }"
          @click="activeTab = 'comments'"
        >
          <i class="pi pi-comments"></i> Comments
        </button>
      </div>

      <div class="tab-content">
        <!-- Auction Tab -->
        <div v-if="activeTab === 'auction'" class="auction-tab">
          <div class="bid-stats">
            <div class="stat-item">
              <span class="label">Highest Bid:</span>
              <span class="value">€{{ bidStats.highest_bid?.toLocaleString() }}</span>
            </div>
            <div class="stat-item">
              <span class="label">Total Bids:</span>
              <span class="value">{{ bidStats.total_bids }}</span>
            </div>
            <div class="stat-item">
              <span class="label">Unique Bidders:</span>
              <span class="value">{{ bidStats.unique_bidders }}</span>
            </div>
            <div class="stat-item">
              <span class="label">Your Bid:</span>
              <span class="value">€{{ userBid?.toLocaleString() }}</span>
            </div>
          </div>

          <div class="bid-form">
            <h3>Place Your Bid</h3>
            <div class="bid-amount">
              <label>Your Bid Amount:</label>
              <input 
                type="number" 
                v-model.number="yourBid" 
                :min="bidStats.highest_bid + bidIncrements[0]"
              />
            </div>
            
            <div class="quick-buttons">
              <button 
                v-for="inc in bidIncrements" 
                :key="inc"
                @click="increaseBid(inc)"
              >
                +€{{ inc }}
              </button>
            </div>
            
            <button class="submit-bid" @click="submitBid">
              Submit Bid
            </button>
          </div>
        </div>

        <!-- Details Tab -->
        <div v-if="activeTab === 'details'" class="details-tab">
          <h3>Car Specifications</h3>
          <div class="specs-grid">
            <div class="spec-item">
              <span class="spec-label">Brand:</span>
              <span class="spec-value">{{ carData.brand?.manufacturer }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Model:</span>
              <span class="spec-value">{{ carData.model?.model }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Year:</span>
              <span class="spec-value">{{ carData.year }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mileage:</span>
              <span class="spec-value">{{ carData.mileage?.toLocaleString() }} km</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Fuel Type:</span>
              <span class="spec-value">{{ carData.fuel_type }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Transmission:</span>
              <span class="spec-value">{{ carData.transmission }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Engine Size:</span>
              <span class="spec-value">{{ carData.engine_size }}L</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Body Type:</span>
              <span class="spec-value">{{ carData.body_type }}</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Color:</span>
              <span class="spec-value">{{ carData.color }}</span>
            </div>
          </div>
        </div>

        <!-- Comments Tab -->
        <div v-if="activeTab === 'comments'" class="comments-tab">
          <div v-if="!hasCommented" class="comment-form">
            <h3>Leave a Review</h3>
            <div class="form-group">
              <label>Rating:</label>
              <select v-model="commentForm.rating">
                <option v-for="n in 5" :value="n">{{ n }} ★</option>
              </select>
            </div>
            <div class="form-group">
              <label>Comment:</label>
              <textarea 
                v-model="commentForm.comment" 
                placeholder="Share your experience..."
                rows="4"
              ></textarea>
            </div>
            <button class="submit-comment" @click="submitComment">
              Submit Review
            </button>
          </div>
          <div v-else class="already-commented">
            You've already submitted a review for this auction.
          </div>

          <div class="comments-list">
            <h3>Reviews</h3>
            <div v-if="comments.length === 0" class="no-comments">
              No reviews yet
            </div>
            <div v-else>
              <div v-for="comment in comments" :key="comment.id" class="comment">
                <div class="rating">
                  {{ '★'.repeat(comment.rating) }}{{ '☆'.repeat(5 - comment.rating) }}
                </div>
                <div class="comment-text">{{ comment.comment }}</div>
                <div class="comment-date">{{ comment.created_at }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="loading">
    Loading car details...
  </div>
</template>

<style scoped>
.auction-car {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 30px;
}

.car-images {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.image-container {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  aspect-ratio: 16/9;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.nav-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1.5rem;
  transition: all 0.2s;
}

.nav-arrow:hover {
  background: rgba(0, 0, 0, 0.7);
}

.prev {
  left: 15px;
}

.next {
  right: 15px;
}

.auction-status h3 {
  margin: 0;
  padding: 10px 15px;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.auction-status .active {
  background: rgba(40, 167, 69, 0.1);
  color: #28a745;
}

.auction-status .inactive {
  background: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}

.seller-info {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
}

.seller-info h4 {
  margin-top: 0;
  margin-bottom: 10px;
}

.auction-info {
  display: flex;
  flex-direction: column;
}

.tabs {
  display: flex;
  border-bottom: 1px solid #dee2e6;
  margin-bottom: 20px;
}

.tabs button {
  padding: 10px 15px;
  background: none;
  border: none;
  border-bottom: 3px solid transparent;
  cursor: pointer;
  font-weight: 500;
  color: #6c757d;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.tabs button:hover {
  color: #495057;
}

.tabs button.active {
  color: #4361ee;
  border-bottom-color: #4361ee;
}

.bid-stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
  margin-bottom: 25px;
}

.stat-item {
  background: #f8f9fa;
  padding: 12px;
  border-radius: 8px;
}

.stat-item .label {
  display: block;
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 5px;
}

.stat-item .value {
  font-weight: 600;
  font-size: 1.1rem;
}

.bid-form {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
}

.bid-form h3 {
  margin-top: 0;
  margin-bottom: 15px;
}

.bid-amount {
  margin-bottom: 15px;
}

.bid-amount label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
}

.bid-amount input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

.quick-buttons {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.quick-buttons button {
  flex: 1;
  padding: 8px;
  background: #e9ecef;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s;
}

.quick-buttons button:hover {
  background: #dee2e6;
}

.submit-bid {
  width: 100%;
  padding: 12px;
  background: #4361ee;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.submit-bid:hover {
  background: #3a56d4;
}

.specs-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.spec-item {
  display: flex;
  justify-content: space-between;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.spec-label {
  font-weight: 500;
  color: #6c757d;
}

.spec-value {
  font-weight: 600;
}

.comment-form {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.comment-form h3 {
  margin-top: 0;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
}

.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group textarea {
  min-height: 100px;
}

.submit-comment {
  padding: 10px 20px;
  background: #28a745;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.submit-comment:hover {
  background: #218838;
}

.already-commented {
  padding: 15px;
  background: #f8f9fa;
  border-radius: 6px;
  color: #6c757d;
  text-align: center;
  margin-bottom: 20px;
}

.comments-list h3 {
  margin-top: 0;
}

.comment {
  padding: 15px 0;
  border-bottom: 1px solid #eee;
}

.comment:last-child {
  border-bottom: none;
}

.rating {
  color: #ffc107;
  font-size: 1.2rem;
  margin-bottom: 5px;
}

.comment-text {
  margin-bottom: 5px;
}

.comment-date {
  font-size: 0.8rem;
  color: #6c757d;
}

.loading {
  padding: 40px;
  text-align: center;
  color: #6c757d;
}

@media (max-width: 992px) {
  .auction-car {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 576px) {
  .bid-stats,
  .specs-grid {
    grid-template-columns: 1fr;
  }
  
  .tabs {
    flex-wrap: wrap;
  }
  
  .tabs button {
    flex: 1;
    min-width: 100px;
  }
}
</style>