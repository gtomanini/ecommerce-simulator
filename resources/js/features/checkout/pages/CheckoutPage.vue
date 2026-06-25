<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="font-display font-bold text-3xl text-gray-800 mb-5">Where to send the imaginary box 📦</h1>

    <div v-if="preparingGuest" class="bg-white rounded-2xl shadow-sm p-8 flex items-center gap-3 text-gray-500">
      <span class="w-5 h-5 border-[3px] border-gray-200 border-t-orange-500 rounded-full animate-spin"></span>
      Taking you straight to payment…
    </div>

    <form v-else @submit.prevent="handleCheckout" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
      <div class="grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Name</label>
          <input v-model="form.buyer_name" type="text" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Email</label>
          <input v-model="form.buyer_email" type="email" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Phone</label>
          <input v-model="form.buyer_phone" type="tel" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Zip Code</label>
          <input v-model="form.delivery_zip" type="text" required :class="inputClass" />
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="font-semibold text-gray-700 text-sm">Address</label>
        <input v-model="form.delivery_address" type="text" required :class="inputClass" />
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">City</label>
          <input v-model="form.delivery_city" type="text" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">State</label>
          <input v-model="form.delivery_state" type="text" required :class="inputClass" />
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="font-semibold text-gray-700 text-sm">Shipping Method</label>
        <select v-model="form.shipping_method_id" required :class="inputClass">
          <option value="">Select a method</option>
          <option v-for="method in shippingMethods" :key="method.id" :value="method.id">
            {{ method.name }} - ${{ method.base_cost }}
          </option>
        </select>
      </div>

      <button
        type="submit"
        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-display font-bold py-3 rounded-full transition-colors"
      >
        Continue to Payment →
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useOrdersStore } from '@/stores/orders'
import { useCartStore } from '@/stores/cart'
import { useAuthStore } from '@/stores/auth'
import { useApi } from '@/composables/useApi'
import { useRouter } from 'vue-router'

const inputClass =
  'px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition'

const ordersStore = useOrdersStore()
const cartStore = useCartStore()
const authStore = useAuthStore()
const { get } = useApi()
const router = useRouter()
const shippingMethods = ref([])
const preparingGuest = ref(false)

const form = reactive({
  buyer_name: '',
  buyer_email: '',
  buyer_phone: '',
  delivery_address: '',
  delivery_city: '',
  delivery_state: '',
  delivery_zip: '',
  shipping_method_id: '',
})

const prefillFromUser = () => {
  const user = authStore.user
  if (!user) return
  form.buyer_name = user.name || ''
  form.buyer_email = user.email || ''
  form.buyer_phone = user.phone || ''
  form.delivery_address = user.address || ''
  form.delivery_city = user.city || ''
  form.delivery_state = user.state || ''
  form.delivery_zip = user.zip || ''
}

onMounted(async () => {
  // Make sure we have the latest user info first.
  await authStore.fetchUser()

  // Guests skip the whole shipping/registration form — straight to payment.
  if (authStore.user?.is_guest) {
    preparingGuest.value = true
    const order = await ordersStore.createOrder({})
    if (order) {
      cartStore.clearCart()
      router.replace(`/orders/${order.id}/payment`)
    } else {
      router.replace('/cart')
    }
    return
  }

  prefillFromUser()

  const response = await get('/shipping-methods')
  shippingMethods.value = response.data
})

const handleCheckout = async () => {
  const order = await ordersStore.createOrder(form)
  if (!order) return
  cartStore.clearCart()
  // Proceed to the payment screen for the newly created order.
  router.push(`/orders/${order.id}/payment`)
}
</script>
