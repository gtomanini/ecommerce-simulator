import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useApi } from '@/composables/useApi'

export const useAchievementsStore = defineStore('achievements', () => {
  const { get } = useApi()

  const achievements = ref([])
  const loading = ref(false)

  const fetchAchievements = async () => {
    loading.value = true
    try {
      const response = await get('/achievements')
      achievements.value = response.data
    } catch (err) {
      console.error('Failed to fetch achievements:', err)
    } finally {
      loading.value = false
    }
  }

  return {
    achievements,
    loading,
    fetchAchievements,
  }
})
