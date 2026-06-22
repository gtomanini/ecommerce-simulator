import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useApi } from '@/composables/useApi'
import { useNotification } from '@/composables/useNotification'

export const useOrdersStore = defineStore('orders', () => {
  const { get, post } = useApi()
  const { success, error } = useNotification()

  const orders = ref([])
  const currentOrder = ref(null)
  const loading = ref(false)
  const currentPage = ref(1)
  const total = ref(0)

  const recentOrders = computed(() => orders.value.slice(0, 3))

  const fetchOrders = async (page = 1) => {
    loading.value = true
    try {
      const response = await get('/orders', { params: { page, per_page: 10 } })
      orders.value = response.data.data
      currentPage.value = response.data.current_page
      total.value = response.data.total
    } catch (err) {
      console.error('Failed to fetch orders:', err)
    } finally {
      loading.value = false
    }
  }

  const fetchOrder = async (orderId) => {
    loading.value = true
    try {
      const response = await get(`/orders/${orderId}`)
      currentOrder.value = response.data
      return response.data
    } catch (err) {
      error('Failed to fetch order details')
    } finally {
      loading.value = false
    }
  }

  const createOrder = async (orderData) => {
    loading.value = true
    try {
      const response = await post('/orders', orderData)
      orders.value.unshift(response.data)
      success('Order created successfully!')
      return response.data
    } catch (err) {
      error(err.response?.data?.message || 'Failed to create order')
      return null
    } finally {
      loading.value = false
    }
  }

  const payOrder = async (orderId, paymentData) => {
    loading.value = true
    try {
      const response = await post(`/orders/${orderId}/payment`, paymentData)
      currentOrder.value = response.data
      success('Payment completed successfully!')
      return response.data
    } catch (err) {
      error(err.response?.data?.message || 'Payment failed')
      return null
    } finally {
      loading.value = false
    }
  }

  const nextPage = () => {
    if (currentPage.value * 10 < total.value) {
      fetchOrders(currentPage.value + 1)
    }
  }

  const prevPage = () => {
    if (currentPage.value > 1) {
      fetchOrders(currentPage.value - 1)
    }
  }

  return {
    orders,
    currentOrder,
    loading,
    currentPage,
    total,
    recentOrders,
    fetchOrders,
    fetchOrder,
    createOrder,
    payOrder,
    nextPage,
    prevPage,
  }
})
