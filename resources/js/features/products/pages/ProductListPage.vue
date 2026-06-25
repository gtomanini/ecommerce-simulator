<template>
  <div class="space-y-5">
    <!-- Categories -->
    <div class="bg-white rounded-2xl shadow-sm p-4">
      <div class="flex gap-5 overflow-x-auto pb-1">
        <button
          v-for="cat in categoryItems"
          :key="cat.id ?? 'all'"
          @click="selectCategory(cat.id)"
          class="flex flex-col items-center gap-1.5 shrink-0 group"
        >
          <span
            class="w-14 h-14 rounded-full flex items-center justify-center text-2xl transition-transform group-hover:scale-110 ring-2"
            :class="selectedCat === cat.id ? 'ring-orange-500' : 'ring-transparent'"
            :style="{ background: cat.bg }"
          >{{ cat.emoji }}</span>
          <span class="text-xs font-semibold text-gray-700">{{ cat.name }}</span>
        </button>
      </div>
    </div>

    <!-- Hero -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 text-white p-8 md:p-10">
      <div class="relative z-10 max-w-3xl">
        <p class="text-yellow-300 font-display font-bold tracking-widest text-sm mb-2">✶ THE GREAT SIMULATION</p>
        <h1 class="font-display font-bold text-4xl md:text-5xl leading-none">
          BUY EVERYTHING. <span class="text-yellow-300">OWN NOTHING.</span>
        </h1>
        <p class="mt-3 text-orange-50">Your wallet will never know. Your brain absolutely will. 🧠</p>
        <button
          @click="scrollToDeals"
          class="mt-5 bg-white text-orange-600 font-display font-bold px-6 py-2.5 rounded-full hover:bg-orange-50 transition-colors"
        >
          Feed the Beast →
        </button>
      </div>

      <!-- Willpower countdown -->
      <div class="absolute top-8 right-8 hidden lg:block text-right">
        <p class="text-yellow-300 font-display font-bold tracking-wider text-xs mb-2">⚡ YOUR WILLPOWER DIES IN</p>
        <div class="flex gap-2 justify-end">
          <div v-for="b in countdownBoxes" :key="b.label" class="bg-black/25 rounded-lg px-3 py-2 text-center min-w-[64px]">
            <div class="font-display font-bold text-2xl">{{ b.value }}</div>
            <div class="text-[10px] tracking-wider text-orange-100">{{ b.label }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Willpower Destroyer (catalog) -->
    <div ref="dealsRef" class="bg-white rounded-2xl shadow-sm p-5">
      <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
        <div class="flex items-center gap-2">
          <h2 class="font-display font-bold text-2xl text-gray-800">⚡ Willpower Destroyer</h2>
          <div class="flex gap-1">
            <span v-for="b in countdownBoxes" :key="b.label" class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded">{{ b.value }}</span>
          </div>
        </div>
        <select
          v-model="sortByInput"
          @change="handleSortChange"
          class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 outline-none"
        >
          <option value="name">Sort: Name</option>
          <option value="price_asc">Cheapest hit first</option>
          <option value="price_desc">Most expensive thrill</option>
          <option value="newest">Freshest dopamine</option>
        </select>
      </div>

      <div v-if="productsStore.loading" class="flex flex-col items-center justify-center py-16 gap-3 text-gray-500">
        <div class="w-10 h-10 border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
        <p>Loading temptations…</p>
      </div>

      <div v-else-if="productsStore.products.length === 0" class="text-center py-16 text-gray-500">
        Nothing here. Try another craving.
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <ProductCard v-for="product in productsStore.products" :key="product.id" :product="product" />
      </div>

      <!-- Pagination -->
      <div v-if="productsStore.products.length" class="flex items-center justify-center gap-4 mt-6">
        <button
          :disabled="productsStore.currentPage === 1"
          @click="productsStore.prevPage"
          class="px-4 py-2 rounded-lg bg-orange-500 text-white font-semibold disabled:bg-gray-300 transition-colors"
        >Previous</button>
        <span class="text-sm text-gray-500">
          Page {{ productsStore.currentPage }} of {{ Math.max(1, Math.ceil(productsStore.total / productsStore.perPage)) }}
        </span>
        <button
          :disabled="productsStore.currentPage * productsStore.perPage >= productsStore.total"
          @click="productsStore.nextPage"
          class="px-4 py-2 rounded-lg bg-orange-500 text-white font-semibold disabled:bg-gray-300 transition-colors"
        >Next</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useProductsStore } from '@/stores/products'
import { useCountdown } from '@/composables/useCountdown'
import ProductCard from '@/features/products/components/ProductCard.vue'

const productsStore = useProductsStore()
const sortByInput = ref('name')
const selectedCat = ref(null)
const dealsRef = ref(null)

const { hours, minutes, seconds } = useCountdown()
const countdownBoxes = computed(() => [
  { value: hours.value, label: 'HRS' },
  { value: minutes.value, label: 'MIN' },
  { value: seconds.value, label: 'SEC' },
])

const CIRCLE_BG = ['#dbeafe', '#fce7f3', '#dcfce7', '#f3e8ff', '#fef9c3', '#e0f2fe', '#fee2e2', '#ffedd5', '#cffafe']

const categoryEmoji = (name) => {
  const n = (name || '').toLowerCase()
  if (n.includes('electron')) return '📱'
  if (n.includes('cloth') || n.includes('fashion')) return '👗'
  if (n.includes('home') || n.includes('decor')) return '🏠'
  if (n.includes('beauty') || n.includes('care')) return '💄'
  if (n.includes('toy')) return '🧸'
  if (n.includes('sport')) return '⚽'
  if (n.includes('pet')) return '🐾'
  if (n.includes('auto') || n.includes('car')) return '🚗'
  if (n.includes('air') || n.includes('plane')) return '✈️'
  if (n.includes('book')) return '📚'
  if (n.includes('shoe')) return '👟'
  if (n.includes('kitchen')) return '🍳'
  if (n.includes('accessor')) return '👜'
  return '🛍️'
}

const categoryItems = computed(() => [
  { id: null, name: 'All', emoji: '✨', bg: '#fef3c7' },
  ...productsStore.categories.map((c, i) => ({
    id: c.id,
    name: c.name,
    emoji: categoryEmoji(c.name),
    bg: CIRCLE_BG[i % CIRCLE_BG.length],
  })),
])

onMounted(() => {
  productsStore.fetchProducts()
  productsStore.fetchCategories()
})

const selectCategory = (id) => {
  selectedCat.value = id
  productsStore.selectedCategory = id
  productsStore.fetchProducts()
}

const handleSortChange = () => {
  productsStore.sortBy = sortByInput.value
  productsStore.fetchProducts()
}

const scrollToDeals = () => dealsRef.value?.scrollIntoView({ behavior: 'smooth' })
</script>
