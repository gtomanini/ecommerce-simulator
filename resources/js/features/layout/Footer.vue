<template>
  <footer class="bg-gray-900 text-gray-300 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-10">
      <div class="grid md:grid-cols-3 gap-8">
        <div>
          <div class="font-display font-bold text-xl text-white">ShopSim</div>
          <p class="text-xs font-bold tracking-widest text-orange-400 uppercase mt-1">Fake Shopping. Real Dopamine.</p>
          <p class="text-sm text-gray-400 mt-3 max-w-xs">
            All the thrill of online shopping. None of the bill. None of the boxes. Spend nothing, feel everything.
          </p>
        </div>
        <div>
          <h4 class="text-white font-display font-semibold mb-3">The Habit</h4>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/" class="hover:text-orange-400">Products</router-link></li>
            <li><router-link to="/cart" class="hover:text-orange-400">Your Pile</router-link></li>
            <li><router-link to="/orders" class="hover:text-orange-400">Orders</router-link></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-display font-semibold mb-3">Support (lol)</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-orange-400">Help Center</a></li>
            <li><a href="#" class="hover:text-orange-400">Contact the Void</a></li>
            <li><a href="#" class="hover:text-orange-400">Privacy</a></li>
          </ul>
        </div>
      </div>
      <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-500">
        &copy; 2026 ShopSim. No purchase was real. No wallet was harmed.
      </div>
    </div>
  </footer>
</template>
