<template>
  <div>
    <h1 class="font-display font-bold text-2xl text-gray-800 text-center mb-6">Welcome back, shopper</h1>

    <form @submit.prevent="handleLogin" class="flex flex-col gap-4">
      <div class="flex flex-col gap-1.5">
        <label for="email" class="font-semibold text-gray-700 text-sm">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="your@email.com"
          required
          class="px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
        />
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="password" class="font-semibold text-gray-700 text-sm">Password</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          placeholder="••••••••"
          required
          class="px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
        />
      </div>

      <button
        type="submit"
        :disabled="isLoading"
        class="mt-1 py-2.5 bg-orange-500 hover:bg-orange-600 disabled:opacity-60 text-white font-display font-semibold rounded-lg transition-colors"
      >
        {{ isLoading ? 'Logging in...' : 'Login' }}
      </button>
    </form>

    <div class="flex items-center gap-3 my-5 text-gray-400 text-xs">
      <span class="flex-1 border-t border-gray-200"></span>
      <span>or</span>
      <span class="flex-1 border-t border-gray-200"></span>
    </div>

    <button
      type="button"
      :disabled="isLoading"
      @click="handleGuest"
      class="w-full py-3 bg-yellow-400 hover:bg-yellow-500 disabled:opacity-60 text-orange-900 font-display font-bold rounded-lg transition-colors active:scale-[0.98]"
    >
      😤 No patience for login — just let me pay
    </button>
    <p class="text-center text-xs text-gray-400 mt-2">Skip the account. Shop and "checkout" as a guest.</p>

    <p class="text-center text-sm text-gray-500 mt-5">
      Don't have an account?
      <router-link to="/auth/register" class="text-orange-500 font-semibold hover:underline">Register here</router-link>
    </p>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useAuthStore } from '@/stores/auth'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const { login, guestLogin } = useAuth()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: '',
})

const isLoading = computed(() => authStore.loading)

const redirectAfterAuth = () => router.push(route.query.redirect || '/')

const handleLogin = async () => {
  if (await login(form.email, form.password)) {
    redirectAfterAuth()
  }
}

const handleGuest = async () => {
  if (await guestLogin()) {
    redirectAfterAuth()
  }
}
</script>
