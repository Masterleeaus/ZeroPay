import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

const slides = [
  {
    emoji: '💸',
    title: 'Get Paid Instantly',
    body: 'Generate a QR code and receive payments from anyone, anywhere.',
  },
  {
    emoji: '📷',
    title: 'Pay Anyone',
    body: 'Scan any ZeroPay QR and complete your payment in seconds.',
  },
  {
    emoji: '📊',
    title: 'Track Everything',
    body: 'Full transaction history and real-time payment notifications.',
  },
]

export default function Onboard() {
  const [current, setCurrent] = useState(0)
  const navigate = useNavigate()

  const finish = () => {
    localStorage.setItem('onboarding_complete', '1')
    navigate('/auth/login', { replace: true })
  }

  const next = () => {
    if (current < slides.length - 1) setCurrent(current + 1)
    else finish()
  }

  const slide = slides[current]

  return (
    <div style={{
      display: 'flex', flexDirection: 'column', minHeight: '100vh',
      background: '#fff', padding: '24px',
    }}>
      <button onClick={finish} style={{
        alignSelf: 'flex-end', background: 'none', border: 'none',
        color: '#666', fontSize: '14px',
      }}>
        Skip
      </button>

      <div style={{
        flex: 1, display: 'flex', flexDirection: 'column',
        alignItems: 'center', justifyContent: 'center', textAlign: 'center',
      }}>
        <div style={{ fontSize: '80px', marginBottom: '24px' }}>{slide.emoji}</div>
        <h2 style={{ fontSize: '1.8rem', fontWeight: 700, color: '#1a1a2e', margin: '0 0 12px' }}>
          {slide.title}
        </h2>
        <p style={{ color: '#666', fontSize: '1rem', maxWidth: '280px', lineHeight: 1.6 }}>
          {slide.body}
        </p>
      </div>

      {/* Dots */}
      <div style={{ display: 'flex', justifyContent: 'center', gap: '8px', marginBottom: '24px' }}>
        {slides.map((_, i) => (
          <div key={i} style={{
            width: 8, height: 8, borderRadius: '50%',
            background: i === current ? '#e94560' : '#ddd',
            cursor: 'pointer',
          }} onClick={() => setCurrent(i)} />
        ))}
      </div>

      <button onClick={next} style={{
        background: '#1a1a2e', color: '#fff', border: 'none',
        borderRadius: '12px', padding: '16px', fontSize: '16px',
        fontWeight: 600, width: '100%',
      }}>
        {current < slides.length - 1 ? 'Next' : 'Get Started'}
      </button>
    </div>
  )
}
