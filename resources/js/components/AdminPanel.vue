<script setup>
    import { ref, onMounted, watch, computed } from 'vue'
    import axios from 'axios'
    import { useRoute } from 'vue-router'

const products = [
  { id: 101, name: 'Car Part A', price: 29.99, stock: 45, category: 'Engine' },
  { id: 102, name: 'Car Part B', price: 59.99, stock: 12, category: 'Transmission' },
  { id: 103, name: 'Car Part C', price: 19.99, stock: 89, category: 'Suspension' }
];



let activeTab = ref('dashboard');

const dashBoardData = ref([])
const usersData = ref([])
const isLoading = ref(true)

async function fetchDashboardData() {
  try {
    const response = await axios.get('/getDashboardAdmin')
    dashBoardData.value = response.data
  } catch (error) {
    console.error('Failed to fetch dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

watch(usersData, (newUsers) => {
  newUsers.forEach(user => {
    watch(() => user.isAdmin, async (newRole, oldRole) => {
      if (newRole !== oldRole) {
        try {
          await axios.put(`/updateUserRole/${user.id}`, {
            role: newRole
          })
          alert(`User ${user.id} role updated to ${newRole}`)
        } catch (error) {
          console.error('Failed to update user role:', error)
          user.isAdmin = oldRole
        }
      }
    }, { deep: true })
  })
}, { deep: true })

async function fetchUserData() {
  try {
    const response = await axios.get('/getUsersAdmin')
    usersData.value = response.data.map(user => ({
      ...user,
      isAdmin: user.isAdmin === 1 ? 'admin' : 'user'
    }))
  } catch (error) {
    console.error('Failed to fetch user data:', error)
  } finally {
    isLoading.value = false
  }
}
async function deleteUser(deleteUser) {
  try {
    const response = await axios.delete(`/deleteUserAdmin/${deleteUser.id}`)
    fetchUserData()
  } catch (error) {
    console.error('Failed to delete user:', error)
  }
}



onMounted(() => {
  fetchDashboardData()
  fetchUserData()
})
</script>

<template>
  <main class="admin-panel">
    <div class="admin-header">
      <h1>Admin Dashboard</h1>
      <div class="admin-actions">
        <RouterLink to="/" class="nav-link">Return</RouterLink>
                      
      </div>
    </div>

    <div class="admin-layout">
      <aside class="admin-sidebar">
        <nav>
          <ul>
            <li :class="{ active: activeTab === 'dashboard' }" @click="activeTab = 'dashboard'">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </li>
            <li :class="{ active: activeTab === 'users' }" @click="activeTab = 'users'">
              <i class="fas fa-users"></i> Users
            </li>
            <li :class="{ active: activeTab === 'products' }" @click="activeTab = 'products'">
              <i class="fas fa-boxes"></i> Auctions
            </li>
          </ul>
        </nav>
      </aside>

      <div class="admin-content">
        <!-- Dashboard Tab -->
        <div v-if="activeTab === 'dashboard'" class="dashboard">
          <div class="stats-grid">
            <div class="stat-card">
              <h3>Total Users</h3>
              <p>{{ dashBoardData.user_count }}</p>
            </div>
            <div class="stat-card">
              <h3>Total Auctions</h3>
              <p>{{ dashBoardData.auction_count }}</p>
            </div>
            <div class="stat-card">
              <h3>Total unique Bids</h3>
              <p>{{ dashBoardData.unique_bid_count }}</p>
            </div>
            <div class="stat-card">
              <h3>Total Bids</h3>
              <p>{{ dashBoardData.bid_count }}</p>
            </div>
          </div>

          <div class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="activity-list">
              <div v-for="activity in dashBoardData?.activities" class="activity-item">
                <span class="time">{{ activity.created_at }}</span>
                <span class="action">{{ activity.description }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Users Tab -->
        <div v-if="activeTab === 'users'" class="users">
          <div class="table-header">
            <h2>User Management</h2>

          </div>
          
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in usersData" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                  <select v-model="user.isAdmin">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </select>
                </td>
                <td>
                  <button class="btn btn-sm btn-danger" @click="deleteUser(user)">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Products Tab -->
        <div v-if="activeTab === 'products'" class="products">
          <div class="table-header">
            <h2>Auction Management</h2>
          </div>
          Kādu dienu......
          
          <!-- <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products" :key="product.id">
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>${{ product.price.toFixed(2) }}</td>
                <td>{{ product.stock }}</td>
                <td>{{ product.category }}</td>
                <td>
                  <button class="btn btn-sm btn-danger">Delete</button>
                </td>
              </tr>
            </tbody>
          </table> -->
        </div>

      
      </div>
    </div>
  </main>
</template>

<style scoped>
.admin-panel {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: #2c3e50;
  color: white;
}

.admin-header h1 {
  margin: 0;
  font-size: 1.5rem;
}

.admin-actions .RouterLink {
  margin-left: 0.5rem;
}

.admin-layout {
  display: flex;
  min-height: calc(100vh - 60px);
}

.admin-sidebar {
  width: 250px;
  background: #34495e;
  color: white;
  padding: 1rem 0;
}

.admin-sidebar nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.admin-sidebar nav li {
  padding: 0.75rem 1.5rem;
  cursor: pointer;
  transition: background 0.2s;
}

.admin-sidebar nav li:hover {
  background: #2c3e50;
}

.admin-sidebar nav li.active {
  background: #2980b9;
}

.admin-sidebar nav i {
  margin-right: 0.5rem;
  width: 20px;
  text-align: center;
}

.admin-content {
  flex: 1;
  padding: 1.5rem;
  background: #f5f5f5;
}

/* Common styles */
.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-primary {
  background: #3498db;
  color: white;
}

.btn-primary:hover {
  background: #2980b9;
}

.btn-secondary {
  background: #95a5a6;
  color: white;
}

.btn-secondary:hover {
  background: #7f8c8d;
}

.btn-danger {
  background: #e74c3c;
  color: white;
}

.btn-danger:hover {
  background: #c0392b;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.85rem;
}

.btn-edit {
  background: #f39c12;
  color: white;
}

.btn-edit:hover {
  background: #d35400;
}

.btn-view {
  background: #27ae60;
  color: white;
}

.btn-view:hover {
  background: #219653;
}

/* Dashboard styles */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  text-align: center;
}

.stat-card h3 {
  margin-top: 0;
  color: #7f8c8d;
  font-size: 1rem;
}

.stat-card p {
  margin-bottom: 0;
  font-size: 2rem;
  font-weight: bold;
  color: #2c3e50;
}

.recent-activity {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.activity-list {
  margin-top: 1rem;
}

.activity-item {
  padding: 0.75rem 0;
  border-bottom: 1px solid #eee;
  display: flex;
}

.activity-item:last-child {
  border-bottom: none;
}

.time {
  color: #7f8c8d;
  width: 100px;
}

.action {
  flex: 1;
}

/* Table styles */
.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.data-table th, .data-table td {
  padding: 0.75rem 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.data-table th {
  background: #f8f9fa;
  font-weight: 600;
}

.data-table tr:hover {
  background: #f8f9fa;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.status-badge.active {
  background: #d4edda;
  color: #155724;
}

.status-badge.banned {
  background: #f8d7da;
  color: #721c24;
}

select {
  padding: 0.25rem 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

/* Settings styles */
.settings-form {
  max-width: 600px;
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-group input[type="text"],
.form-group input[type="date"],
.form-group select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.form-actions {
  margin-top: 1.5rem;
  display: flex;
  gap: 1rem;
}

/* Filters */
.filters {
  display: flex;
  gap: 1rem;
}

.filters select, .filters input {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>