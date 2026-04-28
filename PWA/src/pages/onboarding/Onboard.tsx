import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

const SLIDES = [
  {
    emoji: '💳',
    title: 'Pay Anyone, Instantly',
    body: 'Send money to any bank, wallet, or PayID in seconds — no fees, no friction.',
  },
  {
    emoji: '🔒',
    title: 'Bank-Grade Security',
    body: 'Your money and data are protected by enterprise-level encryption at every step.',
  },
  {
    emoji: '🌏',
    title: 'Anywhere, Any Device',
    body: 'ZeroPay works offline and installs directly on your phone — no app store required.',
  },
]

export default function Onboard() {
  const [index, setIndex] = useState(0)
  const navigate = useNavigate()

  const next = () => {
    if (index < SLIDES.length - 1) {
      setIndex(index + 1)
    } else {
      localStorage.setItem('onboarding_complete', '1')
      navigate('/auth/login', { replace: true })
    }
  }

  const skip = () => {
    localStorage.setItem('onboarding_complete', '1')
    navigate('/auth/login', { replace: true })
  }

  const slide = SLIDES[index]

  return (
    <div style={containerStyle}>
      <button onClick={skip} style={skipStyle}>Skip</button>

      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
        <div style={{ fontSize: '96px', marginBottom: '24px' }}>{slide.emoji}</div>
        <h2 style={{ color: '#fff', fontSize: '26px', fontWeight: 700, textAlign: 'center', margin: '0 0 12px' }}>
          {slide.title}
        </h2>
        <p style={{ color: 'rgba(255,255,255,0.65)', textAlign: 'center', fontSize: '16px', maxWidth: '280px', lineHeight: 1.5 }}>
          {slide.body}
        </p>
      </div>

      {/* Dots */}
      <div style={{ display: 'flex', gap: '8px', marginBottom: '32px' }}>
        {SLIDES.map((_, i) => (
          <span
            key={i}
            style={{
              display: 'inline-block',
              width: i === index ? '24px' : '8px',
              height: '8px',
              borderRadius: '4px',
              background: i === index ? '#e94560' : 'rgba(255,255,255,0.3)',
              transition: 'width 0.3s',
            }}
          />
        ))}
      </div>

      <button onClick={next} style={btnStyle}>
        {index < SLIDES.length - 1 ? 'Next' : 'Get Started'}
      </button>
    </div>
  )
}

const containerStyle: React.CSSProperties = {
  display: 'flex',
  flexDirection: 'column',
  minHeight: '100vh',
  background: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
  padding: '24px',
}

const skipStyle: React.CSSProperties = {
  alignSelf: 'flex-end',
  background: 'transparent',
  border: 'none',
  color: 'rgba(255,255,255,0.5)',
  fontSize: '15px',
  cursor: 'pointer',
  padding: '8px',
}

const btnStyle: React.CSSProperties = {
  background: '#e94560',
  color: '#fff',
  border: 'none',
  borderRadius: '12px',
  padding: '16px',
  fontSize: '16px',
  fontWeight: 600,
  cursor: 'pointer',
  width: '100%',
  marginBottom: '16px',
}
