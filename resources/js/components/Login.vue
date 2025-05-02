<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const router = useRouter();

const login = async () => {

  try {
    await axios.post('/login', {
      email: email.value,
      password: password.value

    });

    email.value = '';
    password.value = '';
    location.reload();

  } catch (error) {
    console.error(error);
  }
};


</script>



<template>
    <form @submit.prevent="login" method="POST">
        <RouterLink to="/" class="home-field">
              <img src="/images/car_logo.png" alt="">
              <h1 class="site-name">AutoIzsole.lv</h1>
        </RouterLink>  
        
        <div class="input_field">
            <input 
                type="text"
                placeholder="E-mail"
                v-model="email"
                required/>
            <input 
                type="password" 
                placeholder="Password" 
                v-model="password"
                required/>
        </div>
        
        <RouterLink to="/register" class="register_redirect">Dont Have An Account? Sign Up</RouterLink>   
        <button type="submit" class="submit-btn"><span class="pi pi-sign-in" style="margin: 3px;"></span></button>
        <p v-if="$route.query.registrationSuccess" class="success_message">Registration successful!</p>
    </form>
</template>

<style>
    .success_message {
    margin-top: 10px;
    color: green;
    font-size: 15px;
    font-style: italic;
    font-weight: bold;
    }
    .register_redirect {
        font-size: 11px;

    }
    .home-field{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .login form {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-size: 30px;
    }

    .input_field {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin: 20px;
    }

    .input_field input {
        margin: 10px 0;
        height: 40px;
        width: 250px;
        padding: 0 15px;
        border-radius: 8px; 
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .input_field input:focus {
        outline: none;
        border-color: black;
        box-shadow: 0 0 5px black;
    }

    .input_field input::placeholder {
        color: #888;
    }
    .submit-btn {
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

    .submit-btn:hover {
        background-color: black;
        box-shadow: 0 0 10px black;
    }

    .submit-btn span {
        margin-right: 10px;
    }

</style>
