<template>
  <div>
    <router-link to="/" class="inline-flex items-center gap-1 text-gray-500 hover:text-orange-500 text-sm mb-4">
      ← Back to the temptations
    </router-link>

    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-10 h-10 border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="!product" class="bg-white rounded-2xl shadow-sm p-12 text-center text-gray-500">
      This temptation vanished. <router-link to="/" class="text-orange-500 font-semibold">Find another →</router-link>
    </div>

    <div v-else class="grid md:grid-cols-2 gap-6">
      <!-- Image -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="aspect-square rounded-xl p-8 flex items-center justify-center" :style="{ background: pastel }">
          <img
            v-if="showImage"
            :src="imageUrl"
            :alt="product.name"
            class="w-full h-full object-contain"
            @error="imgError = true"
          />
          <span v-else class="text-7xl">🛍️</span>
        </div>
      </div>

      <!-- Info -->
      <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
        <span v-if="product.category" class="text-xs font-bold uppercase tracking-wider text-gray-400">
          {{ product.category.name }}
        </span>
        <h1 class="font-display font-bold text-3xl text-gray-800 mt-1">{{ product.name }}</h1>

        <div class="flex items-center gap-3 mt-4">
          <span class="font-display font-bold text-3xl text-orange-500">${{ price }}</span>
          <span class="text-lg text-gray-400 line-through">${{ originalPrice }}</span>
          <span class="bg-red-500 text-white text-sm font-bold px-2 py-1 rounded-full">-{{ discount }}%</span>
        </div>

        <p class="text-gray-600 mt-4 leading-relaxed">{{ product.description }}</p>

        <p class="text-sm mt-4" :class="product.stock > 0 ? 'text-green-600' : 'text-red-500'">
          {{ product.stock > 0 ? `${product.stock} units of regret in stock` : 'Out of stock' }}
        </p>

        <button
          @click="addToCart"
          :disabled="product.stock === 0"
          class="mt-6 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white font-display font-bold py-3 rounded-full transition-colors"
        >
          {{ product.stock === 0 ? 'Out of stock' : 'Add to the pile 🛒' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '@/composables/useApi'
import { useCartStore } from '@/stores/cart'
import { useAuth } from '@/composables/useAuth'
import { useNotification } from '@/composables/useNotification'

const route = useRoute()
const router = useRouter()
const { get } = useApi()
const cartStore = useCartStore()
const { isLoggedIn } = useAuth()
const { warning } = useNotification()

const PASTELS = ['#dbeafe', '#dcfce7', '#fef9c3', '#fee2e2', '#f3e8ff', '#ffedd5']

const product = ref(null)
const loading = ref(true)
const imgError = ref(false)

const imageUrl = computed(() => product.value?.images?.[0]?.image_url)
const showImage = computed(() => !!imageUrl.value && !imgError.value)
const price = computed(() => (product.value ? Number(product.value.price).toFixed(2) : '0.00'))
const discount = computed(() => (product.value ? 40 + (product.value.id * 7) % 50 : 0))
const originalPrice = computed(() =>
  product.value ? (product.value.price / (1 - discount.value / 100)).toFixed(2) : '0.00'
)
const pastel = computed(() => (product.value ? PASTELS[product.value.id % PASTELS.length] : '#f3f4f6'))

onMounted(async () => {
  try {
    const response = await get(`/products/${route.params.id}`)
    product.value = response.data
  } catch (err) {
    product.value = null
  } finally {
    loading.value = false
  }
})

const addToCart = () => {
  if (!isLoggedIn.value) {
    warning('Please log in (or grab the guest pass) to start the pile')
    router.push('/auth/login')
    return
  }
  cartStore.addToCart(product.value.id, 1)
}
</script>
