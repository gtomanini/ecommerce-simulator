<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="font-display font-bold text-3xl text-gray-800">Your (Fake) Identity</h1>
    <p class="text-gray-500 mt-1 mb-5">These details pre-fill your checkout — so you can regret faster.</p>

    <form @submit.prevent="handleSave" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
      <div class="grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Name</label>
          <input v-model="form.name" type="text" required :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Email</label>
          <input :value="authStore.user?.email" type="email" disabled :class="[inputClass, 'bg-gray-100 text-gray-500']" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Phone</label>
          <input v-model="form.phone" type="tel" :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">Zip Code</label>
          <input v-model="form.zip" type="text" :class="inputClass" />
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="font-semibold text-gray-700 text-sm">Address</label>
        <input v-model="form.address" type="text" :class="inputClass" />
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">City</label>
          <input v-model="form.city" type="text" :class="inputClass" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label class="font-semibold text-gray-700 text-sm">State</label>
          <input v-model="form.state" type="text" :class="inputClass" />
        </div>
      </div>

      <button
        type="submit"
        :disabled="authStore.loading"
        class="w-full bg-orange-500 hover:bg-orange-600 disabled:opacity-60 text-white font-display font-bold py-3 rounded-full transition-colors"
      >
        {{ authStore.loading ? 'Saving...' : 'Save Profile' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const inputClass =
  'px-3 py-2.5 border border-gray-200 rounded-lg outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition'

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
