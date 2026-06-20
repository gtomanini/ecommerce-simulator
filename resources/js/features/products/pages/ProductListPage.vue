<template>
  <div class="product-list-page">
    <h1>Products</h1>

    <div class="filters">
      <input
        v-model="searchInput"
        type="text"
        placeholder="Search products..."
        class="search-input"
        @change="handleSearch"
      />

      <select
        v-model="selectedCat"
        class="category-select"
        @change="handleCategoryChange"
      >
        <option :value="null">All Categories</option>
        <option v-for="category in productsStore.categories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>

      <select
        v-model="sortByInput"
        class="sort-select"
        @change="handleSortChange"
      >
        <option value="name">Name</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
        <option value="newest">Newest</option>
      </select>
    </div>

    <div v-if="productsStore.loading" class="loading-container">
      <div class="loading"></div>
      <p>Loading products...</p>
    </div>

    <div v-else-if="productsStore.products.length === 0" class="empty-state">
      <p>No products found</p>
    </div>

    <div v-else class="products-grid">
      <ProductCard
        v-for="product in productsStore.products"
        :key="product.id"
        :product="product"
      />
    </div>

    <div class="pagination">
      <button
        :disabled="productsStore.currentPage === 1"
        @click="productsStore.prevPage"
        class="pagination-btn"
      >
        Previous
      </button>
      <span class="page-info">
        Page {{ productsStore.currentPage }} of
        {{ Math.ceil(productsStore.total / productsStore.perPage) }}
      </span>
      <button
        :disabled="
          productsStore.currentPage * productsStore.perPage >= productsStore.total
        "
        @click="productsStore.nextPage"
        class="pagination-btn"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useProductsStore } from '@/stores/products'
import ProductCard from '@/features/products/components/ProductCard.vue'

const productsStore = useProductsStore()
const searchInput = ref('')
const selectedCat = ref(null)
const sortByInput = ref('name')

onMounted(() => {
  productsStore.fetchProducts()
  productsStore.fetchCategories()
})

const handleSearch = () => {
  productsStore.searchQuery = searchInput.value
  productsStore.fetchProducts()
}

const handleCategoryChange = () => {
  productsStore.selectedCategory = selectedCat.value
  productsStore.fetchProducts()
}

const handleSortChange = () => {
  productsStore.sortBy = sortByInput.value
  productsStore.fetchProducts()
}
</script>

<style scoped>
.product-list-page h1 {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #1f2937;
}

.filters {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

.search-input,
.category-select,
.sort-select {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  gap: 1rem;
}

.loading {
  display: inline-block;
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  color: #6b7280;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}

.pagination-btn {
  padding: 0.5rem 1rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  background: #2563eb;
}

.pagination-btn:disabled {
  background: #d1d5db;
  cursor: not-allowed;
}

.page-info {
  color: #6b7280;
  font-size: 0.875rem;
}

@media (max-width: 1024px) {
  .products-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .filters {
    grid-template-columns: 1fr;
  }

  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .products-grid {
    grid-template-columns: 1fr;
  }
}
</style>