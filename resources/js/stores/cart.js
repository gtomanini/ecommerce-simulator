import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useApi } from '@/composables/useApi'
import { useNotification } from '@/composables/useNotification'

export const useCartStore = defineStore('cart', () => {
  const { get, post, put, delete: deleteApi } = useApi()
  const { success, error } = useNotification()

  const items = ref([])
  const loading = ref(false)

  const total = computed(() => {
    return items.value.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2)
  })

  const itemCount = computed(() => {
    return items.value.reduce((sum, item) => sum + item.quantity, 0)
  })

  const fetchCart = async () => {
    loading.value = true
    try {
      const response = await get('/cart')
      items.value = response.data.items || []
    } catch (err) {
      console.error('Failed to fetch cart:', err)
    } finally {
      loading.value = false
    }
  }

  const addToCart = async (productId, quantity = 1, variations = {}) => {
    try {
      await post('/cart', {
        product_id: productId,
        quantity,
        variations,
      })
      success('Product added to cart!')
      await fetchCart()
      return true
    } catch (err) {
      error(err.response?.data?.message || 'Failed to add to cart')
      return false
    }
  }

  const updateCartItem = async (itemId, quantity) => {
    try {
      if (quantity === 0) {
        await deleteApi(`/cart/${itemId}`)
      } else {
        await put(`/cart/${itemId}`, { quantity })
      }
      success(quantity === 0 ? 'Item removed from cart' : 'Cart updated!')
      await fetchCart()
      return true
    } catch (err) {
      error('Failed to update cart')
      return false
    }
  }

  const removeFromCart = async (itemId) => {
    return updateCartItem(itemId, 0)
  }

  const clearCart = () => {
    items.value = []
  }

  return {
    items,
    total,
    itemCount,
    loading,
    fetchCart,
    addToCart,
    updateCartItem,
    removeFromCart,
    clearCart,
  }
})
