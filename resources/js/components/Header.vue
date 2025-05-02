<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const isAuthenticated = ref(false)

onMounted(async () => {
    try {
        const response = await axios.get('/auth-user')
        user.value = response.data.user
        isAuthenticated.value = response.data.isAuthenticated
    } catch (error) {
        user.value = null
        isAuthenticated.value = false
    }
})

async function logout() {
    try {
        await axios.post('/logout') // Laravel logout route
        window.location.reload() // or use router to redirect
    } catch (error) {
        console.error('Logout failed', error)
    }
}

</script>

<template>
    <header>
        <nav>
            <RouterLink to="/" class="logo-container">
                <img src="/images/car_logo.png" alt="">
                <h1 class="site-name">AutoIzsole.lv</h1>
            </RouterLink>       
            <ul>
                <li><RouterLink to="/">Brand's</RouterLink></li>
                <li><RouterLink to="/">About Us</RouterLink></li>
                <li><RouterLink to="/">Contact Us</RouterLink></li>
            </ul>   
          
            <ul class="auth-links">
                <li v-if="!isAuthenticated">
                    <RouterLink to="/login">
                        <i class="pi pi-sign-in" style="font-size: 1.5rem"></i>
                    </RouterLink>
                </li>

                <li v-else class="user-dropdown">
                    {{ user.name }}
                    <ul class="dropdown-menu">
                        <li><RouterLink to="/profile">Profile</RouterLink></li>
                        <li><a href="#" @click.prevent="logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
  
  
          
      </nav>
    </header>
  </template>
  
  <style>

    .user-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-dropdown .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background-color: white;
        border: 1px solid #ccc;
        padding: 10px;
        margin: 0;
        flex-direction: column;
        min-width: 120px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .user-dropdown:hover .dropdown-menu {
        display: flex;
    }

    .dropdown-menu li {
        margin: 5px 0;
        text-align: left;
    }

    .dropdown-menu a {
        text-decoration: none;
        color: black;
        font-weight: 500;
        transition: color 0.2s;
    }


    .dropdown-menu li:hover {
        transform: scale(1.1);
    }




  header {
      border-bottom: 2px solid black;
      width: 100%;
  }
  nav {
      display: flex;
      align-items: center;
  }
  nav ul{
      display: flex;
  }
  nav li{
      margin: 20px;
      transition: transform 0.3s ease, color 0.3s ease;
  }
  nav li a {
      font-weight: bold;
  }
  nav li:hover {
      transform: scale(1.2);
  }
  nav li a.active {
      border-bottom: 2px solid black;
  }

  nav .auth-links {
      margin-left: auto;
  }
  @media (max-width: 768px) {
      nav ul, nav .auth-links {
          display: none;
      }
      nav {
          justify-content: center;
      }
      
      .sidebar {
          display: block;
      }
  
      .sidebar-button {
          transform: translateX(0);
      }
      .card{
          width: 300px;
          height: 400px;
      }
  }
  </style>