import confetti from 'canvas-confetti'

// Shared AudioContext (created lazily on a user gesture).
let audioCtx = null

const COLORS = ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#a855f7']
const CONFETTI_Z = 2000

export function useCelebration() {
  /**
   * Create / resume the AudioContext. MUST be called from within a user
   * gesture (e.g. a click handler) so browsers allow it to play later.
   */
  const primeAudio = () => {
    try {
      if (!audioCtx) {
        const Ctx = window.AudioContext || window.webkitAudioContext
        if (Ctx) audioCtx = new Ctx()
      }
      if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume()
    } catch (e) {
      /* audio not available — ignore */
    }
  }

  // A bright two-note "cha-ching", synthesized so we don't ship an audio file.
  const playChaChing = () => {
    if (!audioCtx) return
    const now = audioCtx.currentTime
    const notes = [880, 1318.5] // A5 then E6
    notes.forEach((freq, i) => {
      const osc = audioCtx.createOscillator()
      const gain = audioCtx.createGain()
      osc.type = 'triangle'
      osc.frequency.value = freq
      const start = now + i * 0.12
      gain.gain.setValueAtTime(0.0001, start)
      gain.gain.exponentialRampToValueAtTime(0.25, start + 0.02)
      gain.gain.exponentialRampToValueAtTime(0.0001, start + 0.35)
      osc.connect(gain).connect(audioCtx.destination)
      osc.start(start)
      osc.stop(start + 0.4)
    })
  }

  const fireConfetti = () => {
    // One big celebratory burst...
    confetti({ particleCount: 150, spread: 90, startVelocity: 45, origin: { y: 0.6 }, colors: COLORS, zIndex: CONFETTI_Z })
    // ...followed by side cannons for ~0.8s.
    const end = Date.now() + 800
    const frame = () => {
      confetti({ particleCount: 6, angle: 60, spread: 55, origin: { x: 0 }, colors: COLORS, zIndex: CONFETTI_Z })
      confetti({ particleCount: 6, angle: 120, spread: 55, origin: { x: 1 }, colors: COLORS, zIndex: CONFETTI_Z })
      if (Date.now() < end) requestAnimationFrame(frame)
    }
    frame()
  }

  const vibrate = () => {
    try {
      if (navigator.vibrate) navigator.vibrate([60, 40, 120])
    } catch (e) {
      /* ignore */
    }
  }

  /** Fire the full celebration: confetti + sound + haptics. */
  const celebrate = () => {
    fireConfetti()
    playChaChing()
    vibrate()
  }

  return { primeAudio, celebrate }
}
