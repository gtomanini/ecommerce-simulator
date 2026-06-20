import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useApi } from '@/composables/useApi'

export const useProductsStore = defineStore('products', () => {
  const { get } = useApi()

  const products = ref([])
  const categories = ref([])
  const loading = ref(false)
  const currentPage = ref(1)
  const perPage = ref(12)
  const total = ref(0)
  const searchQuery = ref('')
  const selectedCategory = ref(null)
  const sortBy = ref('name')

  const filteredProducts = computed(() => products.value)

  const fetchProducts = async (page = 1, options = {}) => {
    loading.value = true
    try {
      const params = {
        per_page: perPage.value,
        page,
        ...(searchQuery.value && { search: searchQuery.value }),
        ...(selectedCategory.value && { category_id: selectedCategory.value }),
        ...(sortBy.value && { sort: sortBy.value }),
        ...options,
      }

      const response = await get('/products', { params })
      products.value = response.data.data
      currentPage.value = response.data.current_page
      total.value = response.data.total
    } catch (err) {
      console.error('Failed to fetch products:', err)
    } finally {
      loading.value = false
    }
  }

  const fetchCategories = async () => {
    try {
      const response = await get('/categories')
      categories.value = response.data
    } catch (err) {
      console.error('Failed to fetch categories:', err)
    }
  }

  const setSearchQuery = (query) => {
    searchQuery.value = query
    currentPage.value = 1
    fetchProducts()
  }

  const setSelectedCategory = (categoryId) => {
    selectedCategory.value = categoryId
    currentPage.value = 1
    fetchProducts()
  }

  const setSortBy = (sort) => {
    sortBy.value = sort
    currentPage.value = 1
    fetchProducts()
  }

  const setPerPage = (count) => {
    perPage.value = count
    currentPage.value = 1
    fetchProducts()
  }

  const nextPage = () => {
    if (currentPage.value * perPage.value < total.value) {
      fetchProducts(currentPage.value + 1)
    }
  }

  const prevPage = () => {
    if (currentPage.value > 1) {
      fetchProducts(currentPage.value - 1)
    }
  }

  return {
    products,
    categories,
    loading,
    currentPage,
    perPage,
    total,
    searchQuery,
    selectedCategory,
    sortBy,
    filteredProducts,
    fetchProducts,
    fetchCategories,
    setSearchQuery,
    setSelectedCategory,
    setSortBy,
    setPerPage,
    nextPage,
    prevPage,
  }
})
