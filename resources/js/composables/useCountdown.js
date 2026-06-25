import { ref, onMounted, onUnmounted } from 'vue'

/**
 * A looping countdown for the playful "your willpower dies in…" timers.
 * Counts down a rolling window (default 2h) and restarts when it hits zero.
 */
export function useCountdown(windowMs = 2 * 60 * 60 * 1000) {
  const hours = ref('00')
  const minutes = ref('00')
  const seconds = ref('00')

  let target = Date.now() + windowMs
  let timer = null

  const pad = (n) => String(n).padStart(2, '0')

  const tick = () => {
    let diff = target - Date.now()
    if (diff <= 0) {
      target = Date.now() + windowMs
      diff = windowMs
    }
    const totalSeconds = Math.floor(diff / 1000)
    hours.value = pad(Math.floor(totalSeconds / 3600))
    minutes.value = pad(Math.floor((totalSeconds % 3600) / 60))
    seconds.value = pad(totalSeconds % 60)
  }

  onMounted(() => {
    tick()
    timer = setInterval(tick, 1000)
  })

  onUnmounted(() => {
    if (timer) clearInterval(timer)
  })

  return { hours, minutes, seconds }
}
