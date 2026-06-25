<template>
  <header class="bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-md sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">
      <!-- Logo -->
      <router-link to="/" class="shrink-0 leading-none">
        <div class="font-display text-2xl font-bold tracking-tight">ShopSim</div>
        <div class="text-[10px] font-bold tracking-widest text-orange-100 uppercase">
          Fake Shopping. Real Dopamine.
        </div>
      </router-link>

      <!-- Search -->
      <form class="flex-1 max-w-2xl mx-auto" @submit.prevent="handleSearch">
        <div class="flex rounded-lg overflow-hidden shadow-sm bg-white">
          <input
            v-model="query"
            type="text"
            placeholder="Type here to accelerate your dopamine dependency..."
            class="flex-1 px-4 py-2.5 text-gray-700 text-sm outline-none"
          />
          <button type="submit" class="bg-orange-500 hover:bg-orange-600 px-5 flex items-center transition-colors">
            🔍
          </button>
        </div>
      </form>

      <!-- Cope balance -->
      <div class="hidden md:flex items-center gap-2 bg-white/15 rounded-full px-4 py-1.5">
        <span class="text-yellow-300 text-lg">⭐</span>
        <div class="leading-tight">
          <div class="text-[10px] font-bold tracking-wider text-orange-100 uppercase">Your Cope</div>
          <div class="font-display font-bold text-base">{{ cope }}</div>
        </div>
      </div>

      <!-- Cart -->
      <router-link to="/cart" class="relative flex items-center gap-1.5">
        <span class="text-2xl">🛒</span>
        <span class="hidden sm:inline font-display font-semibold text-sm">CART</span>
        <span
          v-if="cartItemCount > 0"
          class="absolute -top-2 -right-2 bg-yellow-400 text-orange-900 text-[11px] font-bold rounded-full min-w-[20px] h-5 px-1 flex items-center justify-center"
        >
          {{ cartItemCount }}
        </span>
      </router-link>

      <!-- Account -->
      <template v-if="isLoggedIn">
        <router-link to="/orders" class="hidden lg:inline text-sm font-semibold hover:text-yellow-200">Orders</router-link>
        <router-link to="/profile" class="text-sm font-semibold hover:text-yellow-200 truncate max-w-[8rem]">
          {{ currentUser?.name || 'Account' }}
        </router-link>
        <button @click="handleLogout" class="text-sm font-semibold hover:text-yellow-200">Exit</button>
      </template>
      <router-link v-else to="/auth/login" class="text-sm font-semibold hover:text-yellow-200">Login</router-link>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useCartStore } from '@/stores/cart'
import { useProductsStore } from '@/stores/products'
import { useRouter } from 'vue-router'

const { isLoggedIn, currentUser, logout } = useAuth()
const cartStore = useCartStore()
const productsStore = useProductsStore()
const router = useRouter()

const query = ref('')
const cartItemCount = computed(() => cartStore.itemCount)
// Playful "cope" balance — placeholder for the future virtual-currency feature.
const cope = computed(() => '4,250')

const handleSearch = () => {
  productsStore.searchQuery = query.value
  productsStore.fetchProducts()
  if (router.currentRoute.value.path !== '/') router.push('/')
}

const handleLogout = async () => {
  await logout()
  router.push('/')
}
</script>
