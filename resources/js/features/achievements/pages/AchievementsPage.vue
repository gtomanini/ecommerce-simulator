<template>
  <div class="space-y-5">
    <h1 class="font-display font-bold text-3xl text-gray-800">Badges of Honor 🏆</h1>
    <p class="text-gray-500 -mt-3">Proof you've fake-shopped like a champion.</p>

    <div v-if="achievementsStore.achievements.length === 0" class="bg-white rounded-2xl shadow-sm p-12 text-center">
      <div class="text-6xl mb-3">🏅</div>
      <h2 class="font-display font-bold text-xl text-gray-800">No badges yet</h2>
      <p class="text-gray-500 mt-1">Start the habit and they'll pile up.</p>
    </div>

    <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="(item, i) in achievementsStore.achievements"
        :key="item.id ?? i"
        class="bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4"
      >
        <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-3xl shrink-0">
          {{ badge(item).icon || '🏆' }}
        </div>
        <div class="min-w-0">
          <h3 class="font-display font-semibold text-gray-800 truncate">{{ badge(item).name }}</h3>
          <p class="text-sm text-gray-500">{{ badge(item).description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAchievementsStore } from '@/stores/achievements'

const achievementsStore = useAchievementsStore()

onMounted(() => achievementsStore.fetchAchievements())

// The API returns user achievements with the achievement nested.
const badge = (item) => item.achievement || item
</script>
