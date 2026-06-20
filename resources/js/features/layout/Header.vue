<template>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <router-link to="/" class="logo">
          <span class="logo-icon">🛒</span>
          <span class="logo-text">ShopSim</span>
        </router-link>

        <nav class="nav">
          <router-link to="/" class="nav-link">Products</router-link>
          <template v-if="isLoggedIn">
            <router-link to="/orders" class="nav-link">Orders</router-link>
            <router-link to="/achievements" class="nav-link">Achievements</router-link>
          </template>
        </nav>

        <div class="actions">
          <router-link to="/cart" class="cart-link">
            <span class="cart-icon">🛍️</span>
            <span v-if="cartItemCount > 0" class="badge">{{ cartItemCount }}</span>
          </router-link>

          <template v-if="isLoggedIn">
            <span class="user-name">{{ currentUser?.name }}</span>
            <button @click="handleLogout" class="logout-btn">Logout</button>
          </template>
          <template v-else>
            <router-link to="/auth/login" class="login-link">Login</router-link>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useCartStore } from '@/stores/cart'
import { useRouter } from 'vue-router'

const { isLoggedIn, currentUser, logout } = useAuth()
const cartStore = useCartStore()
const router = useRouter()

const cartItemCount = computed(() => cartStore.itemCount)

const handleLogout = async () => {
  await logout()
  router.push('/')
}
</script>

<style scoped>
.header {
  background: white;
  border-bottom: 1px solid #e5e7eb;
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.5rem;
  font-weight: bold;
  color: #3b82f6;
  text-decoration: none;
}

.logo-icon {
  font-size: 1.75rem;
}

.nav {
  display: flex;
  gap: 2rem;
  flex: 1;
  margin-left: 3rem;
}

.nav-link {
  color: #6b7280;
  transition: color 0.2s;
}

.nav-link:hover,
.nav-link.router-link-active {
  color: #3b82f6;
  font-weight: 500;
}

.actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.cart-link {
  position: relative;
  font-size: 1.5rem;
}

.badge {
  position: absolute;
  top: -0.5rem;
  right: -0.5rem;
  background: #ef4444;
  color: white;
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-weight: bold;
}

.user-name {
  color: #6b7280;
  font-size: 0.875rem;
}

.login-link,
.logout-btn {
  color: #3b82f6;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  transition: background 0.2s;
}

.login-link:hover,
.logout-btn:hover {
  background: #f0f4ff;
}

@media (max-width: 768px) {
  .nav {
    display: none;
  }

  .actions {
    gap: 1rem;
  }

  .user-name {
    display: none;
  }
}
</style>
