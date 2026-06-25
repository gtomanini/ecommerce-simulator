<template>
  <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col">
    <!-- Image -->
    <router-link :to="`/products/${product.id}`" class="block relative aspect-square p-6" :style="{ background: pastel }">
      <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
        -{{ discount }}%
      </span>
      <span
        v-if="isHot"
        class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1"
      >
        🔥 HOT
      </span>
      <img
        v-if="showImage"
        :src="imageUrl"
        :alt="product.name"
        class="w-full h-full object-contain"
        @error="imgError = true"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-5xl">🛍️</div>
    </router-link>

    <!-- Info -->
    <div class="p-4 flex flex-col flex-1">
      <h3 class="font-display font-semibold text-gray-800 leading-tight line-clamp-2 min-h-[2.6em]">
        {{ product.name }}
      </h3>

      <div class="mt-2 flex items-baseline gap-2">
        <span class="text-xl font-display font-bold text-orange-500">${{ price }}</span>
        <span class="text-sm text-gray-400 line-through">${{ originalPrice }}</span>
      </div>

      <button
        @click="addToCart"
        :disabled="product.stock === 0"
        class="mt-3 w-full bg-orange-500 hover:bg-orange-600 disabled:bg-gray-300 text-white font-display font-semibold text-sm py-2 rounded-lg transition-colors"
      >
        {{ product.stock === 0 ? 'Out of stock' : 'Add to the pile' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'
import { useNotification } from '@/composables/useNotification'

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
})

const cartStore = useCartStore()
const { isLoggedIn } = useAuth()
const router = useRouter()
const { warning } = useNotification()

const PASTELS = ['#dbeafe', '#dcfce7', '#fef9c3', '#fee2e2', '#f3e8ff', '#ffedd5']

// Fall back to the emoji when an image URL is broken (404) instead of
// leaking the alt text over the card.
const imgError = ref(false)
const imageUrl = computed(() => props.product.images?.[0]?.image_url)
const showImage = computed(() => !!imageUrl.value && !imgError.value)

const price = computed(() => Number(props.product.price).toFixed(2))
// Deterministic, on-theme fake discount (it's "fake shopping" after all).
const discount = computed(() => 40 + (props.product.id * 7) % 50)
const originalPrice = computed(() => (props.product.price / (1 - discount.value / 100)).toFixed(2))
const isHot = computed(() => props.product.id % 3 === 0)
const pastel = computed(() => PASTELS[props.product.id % PASTELS.length])

const addToCart = () => {
  if (!isLoggedIn.value) {
    warning('Please log in (or grab the guest pass) to start the pile')
    router.push('/auth/login')
    return
  }
  cartStore.addToCart(props.product.id, 1)
}
</script>
