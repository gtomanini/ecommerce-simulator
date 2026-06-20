import { computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

export function useAuth() {
  const authStore = useAuthStore()

  const isLoggedIn = computed(() => !!authStore.token)
  const currentUser = computed(() => authStore.user)

  return {
    isLoggedIn,
    currentUser,
    login: authStore.login,
    register: authStore.register,
    logout: authStore.logout,
  }
}
