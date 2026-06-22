<template>
  <div class="payment-page">
    <h1>Payment</h1>

    <div v-if="order" class="order-summary">
      <div class="summary-row">
        <span>Order</span>
        <strong>{{ order.order_number }}</strong>
      </div>
      <div class="summary-row total">
        <span>Total to pay</span>
        <strong>R$ {{ formatPrice(order.total) }}</strong>
      </div>
    </div>

    <form @submit.prevent="handlePay">
      <div class="form-group">
        <label>Payment Method</label>
        <div class="methods">
          <label
            v-for="m in methods"
            :key="m.value"
            class="method-option"
            :class="{ active: form.method === m.value }"
          >
            <input type="radio" :value="m.value" v-model="form.method" />
            <span class="method-icon">{{ m.icon }}</span>
            <span>{{ m.label }}</span>
          </label>
        </div>
      </div>

      <template v-if="isCard">
        <div class="form-group">
          <label>Cardholder Name</label>
          <input v-model="form.card_holder" type="text" required />
        </div>
        <div class="form-group">
          <label>Card Number</label>
          <input
            :value="form.card_number"
            @input="onCardNumberInput"
            type="text"
            inputmode="numeric"
            maxlength="19"
            placeholder="1234 5678 9012 3456"
            required
          />
        </div>
        <div class="card-row">
          <div class="form-group">
            <label>Expiry (MM/YY)</label>
            <input
              :value="form.card_expiry"
              @input="onExpiryInput"
              type="text"
              inputmode="numeric"
              placeholder="MM/YY"
              maxlength="5"
              required
            />
          </div>
          <div class="form-group">
            <label>CVV</label>
            <input
              :value="form.card_cvv"
              @input="onCvvInput"
              type="text"
              inputmode="numeric"
              maxlength="4"
              placeholder="123"
              required
            />
          </div>
        </div>
      </template>

      <div v-else-if="form.method === 'pix'" class="pix-box">
        <div class="pix-qr">▦</div>
        <p>Scan the QR code or copy the Pix code to pay. This is a simulation — just confirm to complete.</p>
      </div>

      <button type="submit" class="submit-btn" :disabled="ordersStore.loading">
        {{ ordersStore.loading ? 'Processing...' : `Pay R$ ${order ? formatPrice(order.total) : ''}` }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'

const route = useRoute()
const router = useRouter()
const ordersStore = useOrdersStore()

const order = ref(null)

const methods = [
  { value: 'credit_card', label: 'Credit Card', icon: '💳' },
  { value: 'debit_card', label: 'Debit Card', icon: '🏦' },
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
  const payload = { method: form.method }
  if (isCard.value) {
    payload.card_holder = form.card_holder
    payload.card_number = form.card_number.replace(/\s/g, '')
    payload.card_expiry = form.card_expiry
    payload.card_cvv = form.card_cvv
  }

  const result = await ordersStore.payOrder(route.params.id, payload)
  if (result) {
    router.push(`/orders/${route.params.id}`)
  }
}
</script>

<style scoped>
.payment-page {
  padding: 2rem;
  max-width: 600px;
}

.order-summary {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  padding: 1rem 1.25rem;
  margin-bottom: 1.5rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 0.35rem 0;
  color: #6b7280;
}

.summary-row.total {
  border-top: 1px solid #e5e7eb;
  margin-top: 0.35rem;
  padding-top: 0.75rem;
  font-size: 1.1rem;
  color: #111827;
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

input[type='text'] {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}

.methods {
  display: flex;
  gap: 0.75rem;
}

.method-option {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  cursor: pointer;
  font-weight: 400;
}

.method-option.active {
  border-color: #3b82f6;
  background: #eff6ff;
}

.method-option input {
  display: none;
}

.method-icon {
  font-size: 1.5rem;
}

.card-row {
  display: flex;
  gap: 1rem;
}

.card-row .form-group {
  flex: 1;
}

.pix-box {
  text-align: center;
  padding: 1.5rem;
  border: 1px dashed #d1d5db;
  border-radius: 0.5rem;
  color: #6b7280;
}

.pix-qr {
  font-size: 4rem;
  line-height: 1;
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

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
