<template>
  <div class="min-h-screen flex flex-col items-center justify-center p-4 bg-gradient-to-br from-orange-500 to-orange-600">
    <router-link to="/" class="text-center mb-6 text-white leading-none">
      <div class="font-display font-bold text-3xl">ShopSim</div>
      <div class="text-[11px] font-bold tracking-widest text-orange-100 uppercase mt-1">
        Fake Shopping. Real Dopamine.
      </div>
    </router-link>
    <div class="w-full max-w-md bg-white rounded-2xl p-8 shadow-2xl">
      <slot />
    </div>
  </div>
</template>
