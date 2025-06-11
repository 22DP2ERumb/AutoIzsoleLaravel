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
      component: () => import('../js/views/ProfileView.vue'),
      children: [
        {
          path: '',
          component: () => import('../js/components/ProfileSelection.vue'),
        },
        {
          path: ':carId',  // Make sure this matches what you're using
          component: () => import('../js/components/ProfileSellingCar.vue'),
          props: true  // This allows route params to be passed as props
        },
        {
          path: 'settings',
          component: () => import('../js/components/UserSettings.vue'),
        },
      ]
    },
    {
      path: '/addCar',
      component: () => import('../js/views/AddCarView.vue')
    },
    {
      path: '/auction/:carid',
      component: () => import('../js/views/AuctionView.vue'),
      props: true
    }, 
    {
      path: '/aboutus',
      component: () => import('../js/views/AboutUsView.vue'),
    }, 
    {
      path: '/contactus',
      component: () => import('../js/views/ContactUsView.vue'),
    },  
    {
      path: '/adminPanel',
      component: () => import('../js/views/AdminPanelView.vue'),
    },  
  ],
})

export default router