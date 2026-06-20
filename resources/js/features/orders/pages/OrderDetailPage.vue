<template>
  <div class="order-detail">
    <p>Order Detail ({{ $route.params.id }})</p>
    <router-link to="/orders">Back to Orders</router-link>
  </div>
</template>

<style scoped>
.order-detail {
  padding: 2rem;
}
</style>
