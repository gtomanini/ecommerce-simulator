<template>
  <div class="space-y-5">
    <h1 class="font-display font-bold text-3xl text-gray-800">Your Pile 🛒</h1>

    <div v-if="cartStore.loading" class="flex justify-center py-16">
      <div class="w-10 h-10 border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    </div>

    <!-- Empty -->
    <div v-else-if="cartStore.items.length === 0" class="bg-white rounded-2xl shadow-sm p-12 text-center">
      <div class="text-6xl mb-3">🫙</div>
      <h2 class="font-display font-bold text-xl text-gray-800">Your pile is empty</h2>
      <p class="text-gray-500 mt-1">No dopamine queued up. Let's fix that.</p>
      <router-link
        to="/"
        class="inline-block mt-5 bg-orange-500 hover:bg-orange-600 text-white font-display font-semibold px-6 py-2.5 rounded-full transition-colors"
      >
        Start the pile →
      </router-link>
    </div>

    <!-- Items + summary -->
    <div v-else class="grid lg:grid-cols-3 gap-5">
      <div class="lg:col-span-2 space-y-3">
        <div
          v-for="item in cartStore.items"
          :key="item.id"
          class="bg-white rounded-2xl shadow-sm p-4 flex items-center gap-4"
        >
          <div class="w-20 h-20 rounded-xl bg-gray-50 shrink-0 flex items-center justify-center overflow-hidden">
            <img
              v-if="imageOf(item)"
              :src="imageOf(item)"
              :alt="item.product?.name"
              class="w-full h-full object-contain p-1"
            />
            <span v-else class="text-3xl">🛍️</span>
          </div>

          <div class="flex-1 min-w-0">
            <h3 class="font-display font-semibold text-gray-800 truncate">
              {{ item.product?.name || 'Product' }}
            </h3>
            <p class="text-orange-500 font-bold">${{ Number(item.price).toFixed(2) }}</p>

            <div class="flex items-center gap-2 mt-2">
              <button
                @click="dec(item)"
                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-gray-700"
              >−</button>
              <span class="w-8 text-center font-semibold">{{ item.quantity }}</span>
              <button
                @click="inc(item)"
                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-gray-700"
              >+</button>
            </div>
          </div>

          <div class="text-right shrink-0">
            <p class="font-display font-bold text-gray-800">${{ (item.price * item.quantity).toFixed(2) }}</p>
            <button @click="cartStore.removeFromCart(item.id)" class="text-xs text-gray-400 hover:text-red-500 mt-2">
              Remove
            </button>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="bg-white rounded-2xl shadow-sm p-5 h-fit lg:sticky lg:top-24">
        <h2 class="font-display font-bold text-lg text-gray-800 mb-3">Damage Report</h2>
        <div class="flex justify-between text-gray-500 py-1">
          <span>Items ({{ cartStore.itemCount }})</span>
          <span>${{ cartStore.total }}</span>
        </div>
        <div class="flex justify-between text-green-600 py-1">
          <span>You actually pay</span>
          <span class="font-bold">$0.00</span>
        </div>
        <div class="border-t border-gray-100 mt-2 pt-3 flex justify-between items-baseline">
          <span class="font-display font-bold text-gray-800">Dopamine value</span>
          <span class="font-display font-bold text-xl text-orange-500">${{ cartStore.total }}</span>
        </div>
        <router-link
          to="/checkout"
          class="block text-center mt-4 bg-orange-500 hover:bg-orange-600 text-white font-display font-bold py-3 rounded-full transition-colors"
        >
          Proceed to "Checkout" →
        </router-link>
        <p class="text-center text-xs text-gray-400 mt-2">Spend nothing. Feel everything.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()

onMounted(() => cartStore.fetchCart())

const imageOf = (item) => item.product?.images?.[0]?.image_url

const inc = (item) => cartStore.updateCartItem(item.id, item.quantity + 1)
const dec = (item) => cartStore.updateCartItem(item.id, item.quantity - 1)
</script>
