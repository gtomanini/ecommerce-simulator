<template>
  <div>
    <h1 class="font-display font-bold text-2xl text-gray-800 text-center mb-6">Join the simulation</h1>

    <form @submit.prevent="handleRegister" class="flex flex-col gap-4">
      <div class="flex flex-col gap-1.5">
        <label for="name" class="font-semibold text-gray-700 text-sm">Full Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          placeholder="John Doe"
          required
          class="px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition"
        />
      </div>

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

      <div class="flex flex-col gap-1.5">
        <label for="password_confirmation" class="font-semibold text-gray-700 text-sm">Confirm Password</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
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
        {{ isLoading ? 'Creating Account...' : 'Create Account' }}
      </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
      Already have an account?
      <router-link to="/auth/login" class="text-orange-500 font-semibold hover:underline">Login here</router-link>
    </p>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

const router = useRouter()
const { register } = useAuth()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const isLoading = computed(() => false)

const handleRegister = async () => {
  if (form.password !== form.password_confirmation) {
    alert('Passwords do not match')
    return
  }

  const success = await register(form.name, form.email, form.password, form.password_confirmation)
  if (success) {
    router.push('/')
  }
}
</script>
