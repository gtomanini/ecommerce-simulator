<template>
  <div class="product-card">
    <div class="product-image">
      <img
        v-if="product.images && product.images[0]"
        :src="product.images[0].image_url"
        :alt="product.name"
      />
      <div v-else class="image-placeholder">No Image</div>
    </div>

    <div class="product-info">
      <h3 class="product-name">{{ product.name }}</h3>

      <p class="product-category" v-if="product.category">
        {{ product.category.name }}
      </p>

      <p class="product-description">
        {{ product.description.substring(0, 60) }}...
      </p>

      <div class="product-price">
        R$ {{ parseFloat(product.price).toFixed(2) }}
      </div>

      <div class="product-stock" :class="{ 'out-of-stock': product.stock === 0 }">
        {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
      </div>

      <div class="product-actions">
        <router-link :to="`/products/${product.id}`" class="detail-btn">
          View Details
        </router-link>
        <button
          @click="addToCart"
          :disabled="product.stock === 0"
          class="add-btn"
        >
          Add to Cart
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart'
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'
import { useNotification } from '@/composables/useNotification'

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
})

const cartStore = useCartStore()
const { isLoggedIn } = useAuth()
const router = useRouter()
const { warning } = useNotification()

const addToCart = () => {
  if (!isLoggedIn.value) {
    warning('Please log in to add items to cart')
    router.push('/auth/login')
    return
  }
  cartStore.addToCart(props.product.id, 1)
}
</script>

<style scoped>
.product-card {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.product-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

.product-image {
  width: 100%;
  aspect-ratio: 1;
  background: #f3f4f6;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  background: #f9fafb;
}

.product-info {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.product-name {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
  line-height: 1.3;
  height: 2.6em;
  overflow: hidden;
}

.product-category {
  font-size: 0.75rem;
  color: #9ca3af;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}

.product-description {
  font-size: 0.8rem;
  color: #6b7280;
  margin-bottom: 0.75rem;
  line-height: 1.4;
}

.product-price {
  font-size: 1.25rem;
  font-weight: bold;
  color: #3b82f6;
  margin-bottom: 0.5rem;
}

.product-stock {
  font-size: 0.8rem;
  color: #10b981;
  margin-bottom: 1rem;
}

.product-stock.out-of-stock {
  color: #ef4444;
}

.product-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: auto;
}

.detail-btn,
.add-btn {
  flex: 1;
  padding: 0.5rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.8rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
  text-decoration: none;
}

.detail-btn {
  background: #f3f4f6;
  color: #1f2937;
}

.detail-btn:hover {
  background: #e5e7eb;
}

.add-btn {
  background: #3b82f6;
  color: white;
}

.add-btn:hover:not(:disabled) {
  background: #2563eb;
}

.add-btn:disabled {
  background: #d1d5db;
  cursor: not-allowed;
}
</style>
