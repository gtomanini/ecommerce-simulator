<template>
  <div class="orders-page">
    <h1>My Orders</h1>
    <p v-if="ordersStore.orders.length === 0">No orders yet</p>
    <div v-else>
      <p>Total orders: {{ ordersStore.orders.length }}</p>
      <ul>
        <li v-for="order in ordersStore.orders" :key="order.id">
          <router-link :to="`/orders/${order.id}`">
            {{ order.order_number }} - R$ {{ order.total }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useOrdersStore } from '@/stores/orders'

const ordersStore = useOrdersStore()

onMounted(() => {
  ordersStore.fetchOrders()
})
</script>

<style scoped>
.orders-page {
  padding: 2rem;
}

ul {
  list-style: none;
  padding: 0;
}

li {
  margin: 0.5rem 0;
}

a {
  color: #3b82f6;
  text-decoration: none;
}
</style>
