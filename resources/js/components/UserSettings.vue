<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const user = ref({
  name: '',
  email: '',
});
const form = ref({
  name: '',
  email: '',
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});
const errors = ref({});
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Fetch user data when component mounts
onMounted(async () => {
  try {
    const response = await axios.get('/auth-user');
    user.value = response.data.user;
    form.value.name = user.value.name;
    form.value.email = user.value.email;
  } catch (error) {
    errorMessage.value = 'Failed to load user data';
  }
});

const updateProfile = async () => {
  isLoading.value = true;
  errors.value = {};
  successMessage.value = '';
  errorMessage.value = '';
  
  try {
    await axios.put('/updateUser', {
      name: form.value.name,
      email: form.value.email,
    });
    
    user.value.name = form.value.name;
    user.value.email = form.value.email;
    successMessage.value = 'Profile updated successfully';
    setTimeout(() => successMessage.value = '', 3000);
  } catch (error) {
    if (error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value = 'Failed to update profile';
      setTimeout(() => errorMessage.value = '', 3000);
    }
  } finally {
    isLoading.value = false;
  }
};

const updatePassword = async () => {
  isLoading.value = true;
  errors.value = {};
  successMessage.value = '';
  errorMessage.value = '';
  
  try {
    await axios.put('/updateUserPassword', {
      current_password: form.value.current_password,
      new_password: form.value.new_password,
      new_password_confirmation: form.value.new_password_confirmation,
    });
    
    // Reset password fields
    form.value.current_password = '';
    form.value.new_password = '';
    form.value.new_password_confirmation = '';
    
    successMessage.value = 'Password updated successfully';
    setTimeout(() => successMessage.value = '', 3000);
  } catch (error) {
    if (error.response.status === 422) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value = 'Failed to update password';
      setTimeout(() => errorMessage.value = '', 3000);
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="user-settings">
    <h2 class="settings-title">Account Settings</h2>
    
    <!-- Success Message -->
    <div v-if="successMessage" class="alert alert-success">
      {{ successMessage }}
    </div>
    
    <!-- Error Message -->
    <div v-if="errorMessage" class="alert alert-error">
      {{ errorMessage }}
    </div>
    
    <div class="settings-section">
      <h3>Profile Information</h3>
      <form @submit.prevent="updateProfile" class="settings-form">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            id="name"
            type="text"
            v-model="form.name"
            :disabled="isLoading"
          >
          <span class="error" v-if="errors.name">{{ errors.name[0] }}</span>
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            :disabled="isLoading"
          >
          <span class="error" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>
        
        <button type="submit" class="btn-primary" :disabled="isLoading">
          <span v-if="isLoading">Saving...</span>
          <span v-else>Save Profile</span>
        </button>
      </form>
    </div>
    
    <div class="settings-section">
      <h3>Change Password</h3>
      <form @submit.prevent="updatePassword" class="settings-form">
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input
            id="current_password"
            type="password"
            v-model="form.current_password"
            :disabled="isLoading"
          >
          <span class="error" v-if="errors.current_password">{{ errors.current_password[0] }}</span>
        </div>
        
        <div class="form-group">
          <label for="new_password">New Password</label>
          <input
            id="new_password"
            type="password"
            v-model="form.new_password"
            :disabled="isLoading"
          >
          <span class="error" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
        </div>
        
        <div class="form-group">
          <label for="new_password_confirmation">Confirm New Password</label>
          <input
            id="new_password_confirmation"
            type="password"
            v-model="form.new_password_confirmation"
            :disabled="isLoading"
          >
        </div>
        
        <button type="submit" class="btn-primary" :disabled="isLoading">
          <span v-if="isLoading">Updating...</span>
          <span v-else>Update Password</span>
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.user-settings {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.settings-title {
  font-size: 1.5rem;
  margin-bottom: 2rem;
  color: #333;
  text-align: center;
}

.settings-section {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.settings-section h3 {
  font-size: 1.1rem;
  margin-bottom: 1rem;
  color: #4361ee;
}

.settings-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-size: 0.9rem;
  color: #555;
}

.form-group input {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group input:focus {
  outline: none;
  border-color: #4361ee;
}

.error {
  color: #e63946;
  font-size: 0.8rem;
}

.btn-primary {
  background: #4361ee;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  align-self: flex-start;
}

.btn-primary:hover {
  background: #3a56d4;
}

.btn-primary:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.alert {
  padding: 12px 16px;
  border-radius: 4px;
  margin-bottom: 20px;
  font-size: 0.9rem;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
  .user-settings {
    padding: 10px;
  }
  
  .settings-section {
    padding: 1rem;
  }
}
</style>