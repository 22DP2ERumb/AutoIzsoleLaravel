<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const repassword = ref('')
const errorMessage = ref('');
const router = useRouter();


const register = async () => {
  if (password.value !== repassword.value) {
    errorMessage.value = 'Passwords do not match!';
    return;
  }

  if (!isStrongPassword(password.value)) {
    errorMessage.value = 'Password must be at least 8 characters, include uppercase, lowercase, a number, and a symbol.';
    return;
  }
  if (!isValidEmail(email.value)) {
    errorMessage.value = 'Email must be valid';
    return;
  }



  try {
    await axios.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value
    });

    name.value = '';
    email.value = '';
    password.value = '';
    repassword.value = '';
    errorMessage.value = '';

    router.push({ path: '/login', query: { registrationSuccess: 'true' } });
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message ||
      error.message ||
      'Registration failed. Please try again.';
  }
};
function isStrongPassword(pw) {
  const minLength = /.{8,}/;
  const upper = /[A-Z]/;
  const lower = /[a-z]/;
  const number = /[0-9]/;
  const symbol = /[^A-Za-z0-9]/;
  return minLength.test(pw) && upper.test(pw) && lower.test(pw) && number.test(pw) && symbol.test(pw);
}
function isValidEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  console.log(regex.test(email))
  return regex.test(email);
}
</script>


<template>
    <form @submit.prevent="register" method="POST">
        <RouterLink to="/" class="register_home-field">
              <img src="/images/car_logo.png" alt="">
              <h1 class="site-name">AutoIzsole.lv</h1>
        </RouterLink>  

        <div class="register_input_field">
            <input 
            type="text"  
            v-model ="name"
            placeholder="Name" 
            required/>

            <input
            type="email"
            v-model="email"
            placeholder="Email"
            required    
            />

            <input
            type="password"
            v-model="password"
            placeholder="Password"
            required    
            />

            <input
            type="password"
            v-model="repassword"
            placeholder="Re-Password"
            required    
            />




        </div>
        <RouterLink to="/login" class="register_redirect">Already have an account? Log in</RouterLink>  
        <p v-if="errorMessage" class="error_message">{{ errorMessage }}</p>

        <button type="submit" class="register_submit-btn"><span class="pi pi-sign-in" style="margin: 3px;"></span></button>
    </form>
</template>

<style>
    .error_message{
        color: red;
        font-size: 15px;
        font-style: italic;
        font-weight: bold;
    }
    .register_home-field{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .register form {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-size: 30px;
    }

    .register_input_field {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin: 20px;
    }

    .register_input_field input {
        margin: 10px 0;
        height: 40px;
        width: 250px;
        padding: 0 15px;
        border-radius: 8px; 
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .register_input_field input:focus {
        outline: none;
        border-color: black;
        box-shadow: 0 0 5px black;
    }

    .register_input_field input::placeholder {
        color: #888;
    }
    .register_submit-btn {
        margin-top: 20px;
        width: 50px;
        height: 45px;
        border: none;
        background-color: grey;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .register_submit-btn:hover {
        background-color: black;
        box-shadow: 0 0 10px black;
    }

    .register_submit-btn span {
        margin-right: 10px;
    }

</style>
