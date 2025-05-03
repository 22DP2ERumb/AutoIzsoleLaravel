<script setup>
    import ProfileSelection from './ProfileSelection.vue'

    import { ref, onMounted } from 'vue';
    import axios from 'axios'
    import { RouterLink, useRoute } from 'vue-router'
    import { useRouter } from 'vue-router'
    
    const router = useRouter()
    const user = ref(null)
    const showGarage = ref(false)
    const sellingCar = ref(false)
    const sellingCarId = ref(null)
    const route = useRoute()
    

    onMounted(async () => {
        try {
            const response = await axios.get('/auth-user')
            user.value = response.data.user
        } catch (error) {
            user.value = null
        }
    })
    
    
</script>

<template>

    <div class="profile-container">
        <div class="profile-content">
            <div class="user-info">
                <a href="">
                    <img class="userImg" src="/images/basicUser.png" alt="">
                    <span v-if="user">{{ user.name }}</span>
                </a>
            </div>

            <ul class="options">
                <li><RouterLink to="/profile">My Garage</RouterLink></li>
                <li>Settings</li>

            </ul>
        </div>
        <ProfileSelection />
        
    </div>
</template>
  
  
<style scoped>
    .profile-main-screen{
        width: 100%;
    }
    .user-info{
        margin-top: 20px;
    }
    .user-info a{
        align-items: center;
        display: flex;
        flex-direction: column;
    }

    .user-info span{
        font-style: italic;
        font-weight: bold;
    }

    .profile-content {
        width: 10%;               /* Takes 25% of parent container */
        align-items: center;
        display: flex;
        flex-direction: column;
        border-right: 2px solid black;
        
    }
    .userImg {
        width: 100px;
        height: 100px;
        border-radius: 50%; /* makes it a circle */
        border: 3px solid #000; /* circle border */
        object-fit: cover; /* keeps image nicely centered/cropped */
    }
    .options {
        margin-top: 50px;
    }
    .options li{
        margin-top: 20px;
        font-weight: bold;
        transition: transform 0.3s ease, color 0.3s ease;
        border-bottom: 1px solid black; /* 👈 Left border */
        cursor: pointer;
    }
    .options li:hover {
      transform: scale(1.2);
  }
    .profile-container{
        margin: 50px;           
        border: 1px solid #000; /* circle border */
        background-color: lightgrey;
        height: 40vw;
        display: flex;
    }
 
</style>
  