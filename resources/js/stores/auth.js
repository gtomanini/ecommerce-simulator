import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useApi } from '@/composables/useApi'
import { useNotification } from '@/composables/useNotification'

export const useAuthStore = defineStore('auth', () => {
  const { post } = useApi()
  const { error, success } = useNotification()

  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token'))
  const loading = ref(false)

  const isAuthenticated = computed(() => !!token.value)

  const register = async (name, email, password, passwordConfirmation) => {
    loading.value = true
    try {
      const response = await post('/auth/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      })
      user.value = response.data.user
      token.value = response.data.token
      localStorage.setItem('auth_token', token.value)
      success('Account created successfully!')
      return true
    } catch (err) {
      error(err.response?.data?.message || 'Registration failed')
      return false
    } finally {
      loading.value = false
    }
  }

  const login = async (email, password) => {
    loading.value = true
    try {
      const response = await post('/auth/login', { email, password })
      user.value = response.data.user
      token.value = response.data.token
      localStorage.setItem('auth_token', token.value)
      success('Logged in successfully!')
      return true
    } catch (err) {
      error(err.response?.data?.message || 'Login failed')
      return false
    } finally {
      loading.value = false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('auth_token')
    success('Logged out successfully!')
  }

  const fetchUser = async () => {
    if (!token.value) return
    try {
      const { get } = useApi()
      const response = await get('/auth/me')
      user.value = response.data
    } catch (err) {
      logout()
    }
  }

  const updateProfile = async (profile) => {
    loading.value = true
    try {
      const { put } = useApi()
      const response = await put('/profile', profile)
      user.value = response.data
      success('Profile updated successfully!')
      return true
    } catch (err) {
      error(err.response?.data?.message || 'Failed to update profile')
      return false
    } finally {
      loading.value = false
    }
  }

  if (token.value) {
    fetchUser()
  }

  return {
    user,
    token,
    loading,
    isAuthenticated,
    register,
    login,
    logout,
    fetchUser,
    updateProfile,
  }
})
