<template>
  <div>
    <router-link to="/orders" class="inline-flex items-center gap-1 text-gray-500 hover:text-orange-500 text-sm mb-4">
      ← Back to orders
    </router-link>

    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-10 h-10 border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="!order" class="bg-white rounded-2xl shadow-sm p-12 text-center text-gray-500">
      Order not found.
    </div>

    <div v-else class="space-y-5">
      <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 class="font-display font-bold text-2xl text-gray-800">{{ order.order_number }}</h1>
          <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
        </div>
        <span class="text-sm font-bold px-3 py-1.5 rounded-full" :class="statusClass(order.status)">{{ order.status }}</span>
      </div>

      <!-- Items -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="font-display font-bold text-lg text-gray-800 mb-3">The Haul</h2>
        <div class="divide-y divide-gray-100">
          <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 py-3">
            <div class="w-14 h-14 rounded-xl bg-gray-50 shrink-0 flex items-center justify-center overflow-hidden">
              <img v-if="item.product?.images?.[0]" :src="item.product.images[0].image_url" :alt="item.product?.name" class="w-full h-full object-contain p-1" />
              <span v-else class="text-2xl">🛍️</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-gray-800 truncate">{{ item.product?.name || 'Product' }}</p>
              <p class="text-sm text-gray-400">Qty {{ item.quantity }} × ${{ Number(item.price).toFixed(2) }}</p>
            </div>
            <p class="font-display font-bold text-gray-800">${{ (item.price * item.quantity).toFixed(2) }}</p>
          </div>
        </div>

        <div class="border-t border-gray-100 mt-3 pt-3 space-y-1 text-sm">
          <div class="flex justify-between text-gray-500"><span>Subtotal</span><span>${{ num(order.subtotal) }}</span></div>
          <div class="flex justify-between text-gray-500"><span>Shipping</span><span>${{ num(order.shipping_cost) }}</span></div>
          <div class="flex justify-between text-green-600"><span>You actually paid</span><span class="font-bold">$0.00</span></div>
          <div class="flex justify-between items-baseline pt-1">
            <span class="font-display font-bold text-gray-800">Dopamine value</span>
            <span class="font-display font-bold text-xl text-orange-500">${{ num(order.total) }}</span>
          </div>
        </div>
      </div>

      <!-- Pay for it (if still pending) -->
      <router-link
        v-if="order.status === 'pending'"
        :to="`/orders/${order.id}/payment`"
        class="block text-center bg-orange-500 hover:bg-orange-600 text-white font-display font-bold py-3 rounded-full transition-colors"
      >
        Finish the ritual — pay (not really) →
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'

const route = useRoute()
const ordersStore = useOrdersStore()

const order = ref(null)
const loading = ref(true)

const num = (v) => Number(v || 0).toFixed(2)
const formatDate = (d) => (d ? new Date(d).toLocaleString() : '')

const statusClass = (status) => {
  const map = {
    pending: 'bg-amber-100 text-amber-700',
    confirmed: 'bg-green-100 text-green-700',
    shipped: 'bg-blue-100 text-blue-700',
    delivered: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-700',
  }
  return map[status] || 'bg-gray-100 text-gray-600'
}

onMounted(async () => {
  order.value = await ordersStore.fetchOrder(route.params.id)
  loading.value = false
})
</script>
