<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const user = ref(null)
const isAuthenticated = ref(false)
const showMobileMenu = ref(false)
const showDropdown = ref(false)

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
        await axios.post('/logout')
        window.location.reload()
    } catch (error) {
        console.error('Logout failed', error)
    }
}

function toggleDropdown() {
    showDropdown.value = !showDropdown.value
}
</script>

<template>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <RouterLink to="/" class="logo">
                    <img src="/images/car_logo.png" alt="AutoIzsole Logo" class="logo-img">
                    <span class="logo-text">AutoIzsole.lv</span>
                </RouterLink>

                <button class="menu-toggle" @click="showMobileMenu = !showMobileMenu">
                    <i class="pi pi-bars"></i>
                </button>

                <div class="nav-links" :class="{ 'mobile-show': showMobileMenu }">
                    <ul class="main-nav">
                        <li>
                            <RouterLink to="/" class="nav-link">Home</RouterLink>
                        </li>
                        <li>
                            <RouterLink to="/about" class="nav-link">About Us</RouterLink>
                        </li>
                        <li>
                            <RouterLink to="/contact" class="nav-link">Contact</RouterLink>
                        </li>
                    </ul>

                    <div class="auth-section">
                        <div v-if="!isAuthenticated" class="auth-link">
                            <RouterLink to="/login" class="login-btn">
                                <i class="pi pi-sign-in"></i>
                                <span>Login</span>
                            </RouterLink>
                        </div>
                        <div v-else class="user-menu" @mouseenter="showDropdown = true" @mouseleave="showDropdown = false">
                            <button class="user-btn" @click="toggleDropdown">
                                <span class="user-name">{{ user.name }}</span>
                                <i class="pi pi-user"></i>
                            </button>
                            <transition name="dropdown">
                                <ul v-show="showDropdown" class="dropdown">
                                    <li>
                                        <RouterLink to="/profile" class="dropdown-link">
                                            <i class="pi pi-user"></i> Profile
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <a href="#" @click.prevent="logout" class="dropdown-link">
                                            <i class="pi pi-sign-out"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </transition>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</template>

<style scoped>


/* Base Styles */
.header {
    background-color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    position: relative;
}

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    gap: 0.75rem;
}

.logo-img {
    height: 40px;
    width: auto;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    transition: var(--transition);
}

.logo:hover .logo-text {
    color: var(--primary);
}

/* Navigation Links */
.nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.main-nav {
    display: flex;
    gap: 1.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-link {
    text-decoration: none;
    color: var(--dark);
    font-weight: 500;
    padding: 0.5rem 0;
    position: relative;
    transition: var(--transition);
}

.nav-link:hover {
    color: var(--primary);
}

.nav-link.router-link-active {
    color: var(--primary);
    font-weight: 600;
}

.nav-link.router-link-active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--primary);
    transform: scaleX(1);
    transition: var(--transition);
}

/* Auth Section */
.auth-section {
    display: flex;
    align-items: center;
}

.login-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: var(--primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: var(--transition);
}

.login-btn:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
}

.user-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: var(--transition);
    color: var(--dark);
    font-weight: 500;
}

.user-btn:hover {
    background-color: var(--light-gray);
}

.user-name {
    margin-right: 0.5rem;
}

/* Dropdown Menu */
.user-menu {
    position: relative;
}

.dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: var(--shadow);
    list-style: none;
    padding: 0.5rem 0;
    margin: 0;
    min-width: 180px;
    overflow: hidden;
    z-index: 100;
}

.dropdown-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    color: var(--dark);
    transition: var(--transition);
}

.dropdown-link:hover {
    background-color: var(--light-gray);
    color: var(--primary);
}

.dropdown-link i {
    font-size: 1rem;
}

/* Dropdown Animation */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Mobile Menu */
.menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--dark);
}

/* Responsive */
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .nav-links {
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        background-color: white;
        flex-direction: column;
        align-items: flex-start;
        padding: 1rem 2rem;
        box-shadow: var(--shadow);
        transform: translateY(-150%);
        transition: transform 0.3s ease;
        z-index: 999;
    }

    .nav-links.mobile-show {
        transform: translateY(0);
    }

    .main-nav {
        flex-direction: column;
        width: 100%;
        gap: 0;
    }

    .main-nav li {
        width: 100%;
        margin: 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .nav-link {
        display: block;
        padding: 1rem 0;
    }

    .auth-section {
        width: 100%;
        padding: 1rem 0;
    }

    .login-btn, .user-btn {
        width: 100%;
        justify-content: center;
    }

    .dropdown {
        position: static;
        width: 100%;
        box-shadow: none;
        margin-top: 0.5rem;
    }
}
</style>