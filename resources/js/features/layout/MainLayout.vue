<template>
  <div class="main-layout">
    <Header />
    <BaitBar />
    <main class="main-content">
      <div class="max-w-7xl mx-auto px-4">
        <slot />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '@/features/layout/Header.vue'
import BaitBar from '@/features/layout/BaitBar.vue'
import Footer from '@/features/layout/Footer.vue'
</script>

<style scoped>
.main-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 1.5rem 0 2.5rem;
}
</style>
