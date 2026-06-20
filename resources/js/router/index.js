import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('@/features/products/pages/ProductListPage.vue'),
    meta: { layout: 'main' },
  },
  {
    path: '/products/:id',
    name: 'product-detail',
    component: () => import('@/features/products/pages/ProductDetailPage.vue'),
    meta: { layout: 'main' },
  },
  {
    path: '/auth/login',
    name: 'login',
    component: () => import('@/features/auth/pages/LoginPage.vue'),
    meta: { layout: 'auth' },
  },
  {
    path: '/auth/register',
    name: 'register',
    component: () => import('@/features/auth/pages/RegisterPage.vue'),
    meta: { layout: 'auth' },
  },
  {
    path: '/cart',
    name: 'cart',
    component: () => import('@/features/cart/pages/CartPage.vue'),
    meta: { layout: 'main', requiresAuth: true },
  },
  {
    path: '/checkout',
    name: 'checkout',
    component: () => import('@/features/checkout/pages/CheckoutPage.vue'),
    meta: { layout: 'main', requiresAuth: true },
  },
  {
    path: '/orders',
    name: 'orders',
    component: () => import('@/features/orders/pages/OrderListPage.vue'),
    meta: { layout: 'main', requiresAuth: true },
  },
  {
    path: '/orders/:id',
    name: 'order-detail',
    component: () => import('@/features/orders/pages/OrderDetailPage.vue'),
    meta: { layout: 'main', requiresAuth: true },
  },
  {
    path: '/achievements',
    name: 'achievements',
    component: () => import('@/features/achievements/pages/AchievementsPage.vue'),
    meta: { layout: 'main', requiresAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } })
  } else {
    next()
  }
})

export default router
