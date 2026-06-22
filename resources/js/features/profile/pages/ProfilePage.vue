<template>
  <div class="profile-page">
    <h1>My Profile</h1>
    <p class="subtitle">
      These details are used to pre-fill your checkout form.
    </p>

    <form @submit.prevent="handleSave">
      <div class="form-group">
        <label>Name</label>
        <input v-model="form.name" type="text" required />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input :value="authStore.user?.email" type="email" disabled />
        <small>Email cannot be changed.</small>
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input v-model="form.phone" type="tel" />
      </div>
      <div class="form-group">
        <label>Address</label>
        <input v-model="form.address" type="text" />
      </div>
      <div class="form-group">
        <label>City</label>
        <input v-model="form.city" type="text" />
      </div>
      <div class="form-group">
        <label>State</label>
        <input v-model="form.state" type="text" />
      </div>
      <div class="form-group">
        <label>Zip Code</label>
        <input v-model="form.zip" type="text" />
      </div>
      <button type="submit" class="submit-btn" :disabled="authStore.loading">
        {{ authStore.loading ? 'Saving...' : 'Save Profile' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const form = reactive({
  name: '',
  phone: '',
  address: '',
  city: '',
  state: '',
  zip: '',
})

const fillForm = () => {
  const user = authStore.user
  if (!user) return
  form.name = user.name || ''
  form.phone = user.phone || ''
  form.address = user.address || ''
  form.city = user.city || ''
  form.state = user.state || ''
  form.zip = user.zip || ''
}

onMounted(async () => {
  await authStore.fetchUser()
  fillForm()
})

const handleSave = async () => {
  await authStore.updateProfile({ ...form })
}
</script>

<style scoped>
.profile-page {
  padding: 2rem;
  max-width: 600px;
}

.subtitle {
  color: #6b7280;
  margin-bottom: 1.5rem;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

small {
  color: #9ca3af;
  margin-top: 0.25rem;
}

input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}

input:disabled {
  background: #f3f4f6;
  color: #6b7280;
}

.submit-btn {
  padding: 0.75rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
