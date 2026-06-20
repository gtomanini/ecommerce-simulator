<template>
  <div class="cart-page">
    <h1>Shopping Cart</h1>
    <p v-if="cartStore.items.length === 0">Your cart is empty</p>
    <div v-else>
      <p>Cart items: {{ cartStore.itemCount }}</p>
      <p>Total: R$ {{ cartStore.total }}</p>
      <router-link to="/checkout" class="checkout-btn">Proceed to Checkout</router-link>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()
</script>

<style scoped>
.cart-page {
  padding: 2rem;
}

.checkout-btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  text-decoration: none;
}
</style>
