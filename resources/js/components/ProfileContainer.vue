<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ProfileSelection from './ProfileSelection.vue'

const user = ref(null)
const isLoading = ref(false)

onMounted(async () => {
    try {
        isLoading.value = true
        const response = await axios.get('/auth-user')
        user.value = response.data.user
    } catch (error) {
        user.value = null
    } finally {
        isLoading.value = false
    }
})
</script>

<template>
    <div class="profile-page">
        <aside class="profile-sidebar">
            <div class="user-profile">
                <div class="avatar-container">
                    <img 
                        class="user-avatar" 
                        src="/images/basicUser.png" 
                        :alt="user?.name || 'User avatar'"
                    >
                </div>
                
                <h2 v-if="user" class="user-name">{{ user.name }}</h2>
                <p v-if="user" class="user-email">{{ user.email }}</p>
            </div>

            <nav class="profile-nav">
                <RouterLink to="/profile" class="nav-link" active-class="active">
                    <i class="pi pi-car"></i>
                    <span>My Garage</span>
                </RouterLink>
                <RouterLink to="/profile/settings" class="nav-link" active-class="active">
                    <i class="pi pi-cog"></i>
                    <span>Settings</span>
                </RouterLink>
            </nav>
        </aside>

        <main class="profile-content">
            <RouterView />
        </main>
    </div>
</template>

<style scoped>
.profile-page {
    display: flex;
    min-height: calc(100vh - 80px);
    background: #f8f9fa;
}

.profile-sidebar {
    width: 280px;
    background: white;
    padding: 30px 20px;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
}

.user-profile {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.avatar-container {
    width: 120px;
    height: 120px;
    margin: 0 auto 15px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #4361ee;
    padding: 5px;
}

.user-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.user-name {
    font-size: 1.25rem;
    margin: 0 0 5px;
    color: #333;
}

.user-email {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

.profile-nav {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 8px;
    text-decoration: none;
    color: #555;
    font-weight: 500;
    transition: all 0.2s ease;
}

.nav-link:hover {
    background: #f0f2f5;
    color: #4361ee;
}

.nav-link.active {
    background: rgba(67, 97, 238, 0.1);
    color: #4361ee;
}

.nav-link i {
    font-size: 1.1rem;
}

.profile-content {
    flex: 1;
    padding: 30px;
    background: white;
    margin: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

@media (max-width: 768px) {
    .profile-page {
        flex-direction: column;
    }
    
    .profile-sidebar {
        width: 100%;
        padding: 20px;
    }
    
    .profile-content {
        margin: 0;
        border-radius: 0;
        padding: 20px;
    }
}
</style>