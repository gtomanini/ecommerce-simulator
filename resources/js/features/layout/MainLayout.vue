<template>
  <div class="main-layout">
    <Header />
    <main class="main-content">
      <div class="container">
        <slot />
      </div>
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '@/features/layout/Header.vue'
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
  padding: 2rem 0;
}
</style>
