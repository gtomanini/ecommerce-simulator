<template>
  <div class="register-page">
    <h1>Create Account</h1>
    <form @submit.prevent="handleRegister">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          placeholder="John Doe"
          required
        />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          placeholder="your@email.com"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          placeholder="••••••••"
          required
        />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          placeholder="••••••••"
          required
        />
      </div>

      <button type="submit" :disabled="isLoading" class="submit-btn">
        <span v-if="isLoading" class="loading"></span>
        {{ isLoading ? 'Creating Account...' : 'Create Account' }}
      </button>
    </form>

    <p class="login-link">
      Already have an account? <router-link to="/auth/login">Login here</router-link>
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

<style scoped>
.register-page h1 {
  font-size: 1.875rem;
  margin-bottom: 2rem;
  text-align: center;
  color: #1f2937;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-weight: 500;
  color: #374151;
}

input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s;
}

input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.submit-btn {
  padding: 0.75rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.submit-btn:hover:not(:disabled) {
  background: #2563eb;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.login-link {
  text-align: center;
  font-size: 0.875rem;
  color: #6b7280;
}

.login-link a {
  color: #3b82f6;
  font-weight: 500;
}
</style>
