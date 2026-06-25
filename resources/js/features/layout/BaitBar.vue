<template>
  <div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center gap-3">
      <span class="text-sm font-bold text-gray-700 shrink-0">🪤 The Bait:</span>
      <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-orange-400 to-orange-500 rounded-full transition-all duration-500"
          :style="{ width: pct + '%' }"
        ></div>
      </div>
      <span class="text-sm font-bold text-orange-500 shrink-0 whitespace-nowrap">{{ message }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'

const cartStore = useCartStore()

// Free-shipping-style gag: a threshold the cart is nudged toward.
const THRESHOLD = 50

const pct = computed(() => Math.min((cartStore.total / THRESHOLD) * 100, 100))

const message = computed(() => {
  const remaining = THRESHOLD - cartStore.total
  if (remaining <= 0) return "you broke. but you're free. 🎉"
  return `just $${remaining.toFixed(2)} more... c'mon.`
})
</script>
