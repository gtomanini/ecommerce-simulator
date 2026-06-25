<template>
  <div class="space-y-5">
    <h1 class="font-display font-bold text-3xl text-gray-800">Your Receipts of Regret 🧾</h1>

    <div v-if="ordersStore.loading" class="flex justify-center py-16">
      <div class="w-10 h-10 border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="ordersStore.orders.length === 0" class="bg-white rounded-2xl shadow-sm p-12 text-center">
      <div class="text-6xl mb-3">🧾</div>
      <h2 class="font-display font-bold text-xl text-gray-800">No orders yet</h2>
      <p class="text-gray-500 mt-1">Nothing fake-bought so far. The void awaits.</p>
      <router-link to="/" class="inline-block mt-5 bg-orange-500 hover:bg-orange-600 text-white font-display font-semibold px-6 py-2.5 rounded-full transition-colors">
        Go feed the beast →
      </router-link>
    </div>

    <div v-else class="space-y-3">
      <router-link
        v-for="order in ordersStore.orders"
        :key="order.id"
        :to="`/orders/${order.id}`"
        class="bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between hover:shadow-md transition-shadow"
      >
        <div>
          <p class="font-display font-semibold text-gray-800">{{ order.order_number }}</p>
          <p class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</p>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-xs font-bold px-2.5 py-1 rounded-full" :class="statusClass(order.status)">
            {{ order.status }}
          </span>
          <span class="font-display font-bold text-orange-500">${{ Number(order.total).toFixed(2) }}</span>
          <span class="text-gray-300">›</span>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useOrdersStore } from '@/stores/orders'

const ordersStore = useOrdersStore()

onMounted(() => ordersStore.fetchOrders())

const formatDate = (d) => (d ? new Date(d).toLocaleDateString() : '')

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
</script>
