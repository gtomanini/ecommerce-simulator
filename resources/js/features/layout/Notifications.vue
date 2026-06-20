<template>
  <div class="notifications-container">
    <transition-group name="notification">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        :class="['notification', notification.type]"
      >
        <span class="icon">{{ getIcon(notification.type) }}</span>
        <span class="message">{{ notification.message }}</span>
        <button @click="removeNotification(notification.id)" class="close-btn">×</button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useNotification } from '@/composables/useNotification'

const { notifications, removeNotification } = useNotification()

const getIcon = (type) => {
  const icons = {
    success: '✓',
    error: '✕',
    info: 'ℹ',
    warning: '⚠',
  }
  return icons[type] || 'ℹ'
}
</script>

<style scoped>
.notifications-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 9999;
  pointer-events: none;
}

.notification {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  margin-bottom: 0.5rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  pointer-events: auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  animation: slideIn 0.3s ease-out;
}

.notification.success {
  background: #d1fae5;
  color: #065f46;
  border-left: 4px solid #10b981;
}

.notification.error {
  background: #fee2e2;
  color: #7f1d1d;
  border-left: 4px solid #ef4444;
}

.notification.info {
  background: #dbeafe;
  color: #0c2340;
  border-left: 4px solid #0ea5e9;
}

.notification.warning {
  background: #fef3c7;
  color: #78350f;
  border-left: 4px solid #f59e0b;
}

.icon {
  font-weight: bold;
  font-size: 1.25rem;
}

.close-btn {
  background: none;
  border: none;
  color: inherit;
  cursor: pointer;
  font-size: 1.5rem;
  margin-left: auto;
  padding: 0;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.close-btn:hover {
  opacity: 1;
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-leave-to {
  transform: translateX(400px);
  opacity: 0;
}
</style>
