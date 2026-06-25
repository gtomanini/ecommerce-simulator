<template>
  <!-- Celebration overlay shown right after a successful payment -->
  <div
    v-if="celebrating"
    class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
    @click="goToOrder"
  >
    <div class="pop-in bg-white rounded-2xl p-10 max-w-sm w-full text-center shadow-2xl" @click.stop>
      <div class="wiggle text-6xl leading-none">🎉</div>
      <h2 class="font-display font-bold text-2xl text-gray-800 mt-3">Purchase complete!</h2>
      <p class="text-gray-500 mt-2">
        You just felt <strong class="text-gray-700">${{ paidTotal }}</strong> of pure dopamine…
      </p>
      <p class="text-green-600 text-lg mt-1">…and paid <strong>$0.00</strong> 😎</p>
      <div class="flex flex-col gap-2 mt-6">
        <button
          class="bg-orange-500 hover:bg-orange-600 text-white font-display font-bold py-3 rounded-full transition-colors"
          @click="goToOrder"
        >
          View my order
        </button>
        <button class="text-orange-500 font-semibold py-2 hover:underline" @click="keepShopping">Keep shopping</button>
      </div>
    </div>
  </div>

  <div class="max-w-xl mx-auto">
    <h1 class="font-display font-bold text-3xl text-gray-800 mb-5">Pretend to Pay 💳</h1>

    <div v-if="order" class="bg-white rounded-2xl shadow-sm p-5 mb-5">
      <div class="flex justify-between text-gray-500 py-1">
        <span>Order</span>
        <strong class="text-gray-700">{{ order.order_number }}</strong>
      </div>
      <div class="flex justify-between items-baseline border-t border-gray-100 mt-1 pt-3">
        <span class="font-display font-bold text-gray-800">Total to "pay"</span>
        <strong class="font-display font-bold text-xl text-orange-500">${{ formatPrice(order.total) }}</strong>
      </div>
    </div>

    <form @submit.prevent="handlePay" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
      <div>
        <label class="font-semibold text-gray-700 text-sm">Payment Method</label>
        <div class="grid grid-cols-3 gap-3 mt-2">
          <label
            v-for="m in methods"
            :key="m.value"
            class="flex flex-col items-center gap-1 py-3 rounded-xl border-2 cursor-pointer transition-colors"
            :class="form.method === m.value ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300'"
          >
            <input type="radio" :value="m.value" v-model="form.method" class="hidden" />
            <span class="text-2xl">{{ m.icon }}</span>
            <span class="text-sm font-semibold text-gray-700">{{ m.label }}</span>
          </label>
        </div>
      </div>

      <template v-if="isCard">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Cardholder Name</label>
          <input v-model="form.card_holder" type="text" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Card Number</label>
          <input
            :value="form.card_number"
            @input="onCardNumberInput"
            type="text"
            inputmode="numeric"
            maxlength="19"
            placeholder="1234 5678 9012 3456"
            required
            :class="inputClass"
          />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="flex flex-col gap-1.5">
            <label class="font-semibold text-gray-700 text-sm">Expiry (MM/YY)</label>
            <input
              :value="form.card_expiry"
              @input="onExpiryInput"
              type="text"
              inputmode="numeric"
              placeholder="MM/YY"
              maxlength="5"
              required
              :class="inputClass"
            />
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="font-semibold text-gray-700 text-sm">CVV</label>
            <input
              :value="form.card_cvv"
              @input="onCvvInput"
              type="text"
              inputmode="numeric"
              maxlength="4"
              placeholder="123"
              required
              :class="inputClass"
            />
          </div>
        </div>
      </template>

      <div v-else-if="form.method === 'pix'" class="text-center p-6 border border-dashed border-gray-300 rounded-xl text-gray-500">
        <div class="text-6xl leading-none">▦</div>
        <p class="mt-2 text-sm">Scan the QR code or copy the Pix code. It's a simulation — just confirm to complete.</p>
      </div>

      <button
        type="submit"
        :disabled="ordersStore.loading"
        class="w-full bg-orange-500 hover:bg-orange-600 disabled:opacity-60 text-white font-display font-bold py-3 rounded-full transition-colors"
      >
        {{ ordersStore.loading ? 'Processing...' : `Pay $${order ? formatPrice(order.total) : ''} (not really)` }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'
import { useCelebration } from '@/composables/useCelebration'

const inputClass =
  'px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition'

const route = useRoute()
const router = useRouter()
const ordersStore = useOrdersStore()
const { primeAudio, celebrate } = useCelebration()

const order = ref(null)
const celebrating = ref(false)
const paidTotal = computed(() => (order.value ? formatPrice(order.value.total) : '0.00'))

const methods = [
  { value: 'credit_card', label: 'Credit', icon: '💳' },
  { value: 'debit_card', label: 'Debit', icon: '🏦' },
  { value: 'pix', label: 'Pix', icon: '⚡' },
]

const form = reactive({
  method: 'credit_card',
  card_holder: '',
  card_number: '',
  card_expiry: '',
  card_cvv: '',
})

const isCard = computed(() => form.method === 'credit_card' || form.method === 'debit_card')

const formatPrice = (value) => Number(value).toFixed(2)

// Card number: keep digits only, group in blocks of 4 -> "1234 5678 9012 3456"
const onCardNumberInput = (e) => {
  const digits = e.target.value.replace(/\D/g, '').slice(0, 16)
  form.card_number = digits.replace(/(\d{4})(?=\d)/g, '$1 ')
  e.target.value = form.card_number
}

// Expiry: keep digits only, auto-insert slash -> "MM/YY"
const onExpiryInput = (e) => {
  const digits = e.target.value.replace(/\D/g, '').slice(0, 4)
  form.card_expiry = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits
  e.target.value = form.card_expiry
}

// CVV: digits only
const onCvvInput = (e) => {
  form.card_cvv = e.target.value.replace(/\D/g, '').slice(0, 4)
  e.target.value = form.card_cvv
}

onMounted(async () => {
  order.value = await ordersStore.fetchOrder(route.params.id)
})

const handlePay = async () => {
  // Unlock audio within this click so the cha-ching can play on success.
  primeAudio()

  const payload = { method: form.method }
  if (isCard.value) {
    payload.card_holder = form.card_holder
    payload.card_number = form.card_number.replace(/\s/g, '')
    payload.card_expiry = form.card_expiry
    payload.card_cvv = form.card_cvv
  }

  const result = await ordersStore.payOrder(route.params.id, payload)
  if (result) {
    celebrating.value = true
    celebrate()
  }
}

const goToOrder = () => router.push(`/orders/${route.params.id}`)
const keepShopping = () => router.push('/')
</script>

<style scoped>
.pop-in {
  animation: pop-in 0.45s cubic-bezier(0.18, 0.89, 0.32, 1.28);
}
.wiggle {
  animation: wiggle 0.8s ease-in-out;
}
@keyframes pop-in {
  0% { transform: scale(0.7); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
@keyframes wiggle {
  0%, 100% { transform: rotate(0); }
  25% { transform: rotate(-12deg) scale(1.1); }
  75% { transform: rotate(12deg) scale(1.1); }
}
</style>
