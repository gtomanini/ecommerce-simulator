import { ref } from 'vue'

const notifications = ref([])

export function useNotification() {
  const addNotification = (message, type = 'info', duration = 3000) => {
    const id = Date.now()
    const notification = { id, message, type }

    notifications.value.push(notification)

    if (duration > 0) {
      setTimeout(() => {
        notifications.value = notifications.value.filter((n) => n.id !== id)
      }, duration)
    }

    return id
  }

  const removeNotification = (id) => {
    notifications.value = notifications.value.filter((n) => n.id !== id)
  }

  const success = (message, duration) => addNotification(message, 'success', duration)
  const error = (message, duration) => addNotification(message, 'error', duration)
  const info = (message, duration) => addNotification(message, 'info', duration)
  const warning = (message, duration) => addNotification(message, 'warning', duration)

  return {
    notifications,
    addNotification,
    removeNotification,
    success,
    error,
    info,
    warning,
  }
}
