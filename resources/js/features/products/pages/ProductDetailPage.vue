<template>
  <div class="product-detail">
    <p>Product Detail Page ({{ $route.params.id }})</p>
    <router-link to="/">Back to Products</router-link>
  </div>
</template>

<style scoped>
.product-detail {
  padding: 2rem;
}
</style>
