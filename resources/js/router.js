import { createRouter, createWebHistory } from 'vue-router'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('../js/views/HomeView.vue'),
    },
    {
      path: '/register',
      component: () => import('../js/views/RegisterView.vue')
    },
    {
      path: '/login',
      component: () => import('../js/views/LoginView.vue')
    },
    {
      path: '/profile',
      component: () => import('../js/views/ProfileView.vue')
    },
    {
      path: '/addCar',
      component: () => import('../js/views/AddCarView.vue')
    },
  ],
})

export default router