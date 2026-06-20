<template>
  <div id="app">
    <MainLayout v-if="route.meta.layout !== 'auth'">
      <RouterView v-slot="{ Component }">
        <Transition name="fade" mode="out-in">
          <component :is="Component" :key="$route.fullPath" />
        </Transition>
      </RouterView>
    </MainLayout>
    <AuthLayout v-else>
      <RouterView v-slot="{ Component }">
        <Transition name="fade" mode="out-in">
          <component :is="Component" :key="$route.fullPath" />
        </Transition>
      </RouterView>
    </AuthLayout>
    <Notifications />
  </div>
</template>

<script setup>
import { useRoute, RouterView } from 'vue-router'
import MainLayout from '@/features/layout/MainLayout.vue'
import AuthLayout from '@/features/layout/AuthLayout.vue'
import Notifications from '@/features/layout/Notifications.vue'

const route = useRoute()
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
