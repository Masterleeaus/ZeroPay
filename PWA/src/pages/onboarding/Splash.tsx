import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

export default function Splash() {
  const navigate = useNavigate()

  useEffect(() => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      navigate('/dashboard', { replace: true })
      return
    }
    const onboardingDone = localStorage.getItem('onboarding_complete')
    const timer = setTimeout(() => {
      navigate(onboardingDone ? '/auth/login' : '/onboard', { replace: true })
    }, 2000)
    return () => clearTimeout(timer)
  }, [navigate])

  return (
    <div style={containerStyle}>
      <div style={{ fontSize: '72px', marginBottom: '16px' }}>💳</div>
      <h1 style={{ color: '#fff', fontSize: '32px', fontWeight: 700, margin: 0 }}>ZeroPay</h1>
      <p style={{ color: 'rgba(255,255,255,0.6)', marginTop: '8px', fontSize: '16px' }}>
        Fast. Secure. Seamless.
      </p>
      <div style={dotContainerStyle}>
        <span style={dotStyle} />
        <span style={{ ...dotStyle, opacity: 0.5 }} />
        <span style={{ ...dotStyle, opacity: 0.25 }} />
      </div>
    </div>
  )
}

const containerStyle: React.CSSProperties = {
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  justifyContent: 'center',
  minHeight: '100vh',
  background: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
  animation: 'fadeIn 0.5s ease-in',
}

const dotContainerStyle: React.CSSProperties = {
  display: 'flex',
  gap: '8px',
  marginTop: '40px',
}

const dotStyle: React.CSSProperties = {
  display: 'inline-block',
  width: '8px',
  height: '8px',
  borderRadius: '50%',
  background: '#e94560',
  animation: 'pulse 1.5s infinite',
}
