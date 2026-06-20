<template>
  <div class="checkout-page">
    <h1>Checkout</h1>
    <form @submit.prevent="handleCheckout">
      <div class="form-group">
        <label>Name</label>
        <input v-model="form.buyer_name" type="text" required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input v-model="form.buyer_email" type="email" required />
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input v-model="form.buyer_phone" type="tel" required />
      </div>
      <div class="form-group">
        <label>Address</label>
        <input v-model="form.delivery_address" type="text" required />
      </div>
      <div class="form-group">
        <label>City</label>
        <input v-model="form.delivery_city" type="text" required />
      </div>
      <div class="form-group">
        <label>State</label>
        <input v-model="form.delivery_state" type="text" required />
      </div>
      <div class="form-group">
        <label>Zip Code</label>
        <input v-model="form.delivery_zip_code" type="text" required />
      </div>
      <div class="form-group">
        <label>Shipping Method</label>
        <select v-model="form.shipping_method_id" required>
          <option value="">Select a method</option>
          <option v-for="method in shippingMethods" :key="method.id" :value="method.id">
            {{ method.name }} - R$ {{ method.base_cost }}
          </option>
        </select>
      </div>
      <button type="submit" class="submit-btn">Place Order</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useOrdersStore } from '@/stores/orders'
import { useCartStore } from '@/stores/cart'
import { useApi } from '@/composables/useApi'
import { useRouter } from 'vue-router'

const ordersStore = useOrdersStore()
const cartStore = useCartStore()
const { get } = useApi()
const router = useRouter()
const shippingMethods = ref([])

const form = reactive({
  buyer_name: '',
  buyer_email: '',
  buyer_phone: '',
  delivery_address: '',
  delivery_city: '',
  delivery_state: '',
  delivery_zip_code: '',
  shipping_method_id: '',
})

onMounted(async () => {
  const response = await get('/shipping-methods')
  shippingMethods.value = response.data
})

const handleCheckout = async () => {
  await ordersStore.createOrder(form)
  cartStore.clearCart()
  router.push('/orders')
}
</script>

<style scoped>
.checkout-page {
  padding: 2rem;
  max-width: 600px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

input,
select {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}

.submit-btn {
  padding: 0.75rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
}
</style>
