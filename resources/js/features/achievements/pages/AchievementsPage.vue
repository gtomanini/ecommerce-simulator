<template>
  <div class="achievements-page">
    <h1>Achievements</h1>
    <p v-if="achievementsStore.achievements.length === 0">No achievements yet</p>
    <p v-else>Total achievements: {{ achievementsStore.achievements.length }}</p>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAchievementsStore } from '@/stores/achievements'

const achievementsStore = useAchievementsStore()

onMounted(() => {
  achievementsStore.fetchAchievements()
})
</script>

<style scoped>
.achievements-page {
  padding: 2rem;
}
</style>
