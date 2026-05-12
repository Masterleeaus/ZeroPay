import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

export default function Splash() {
  const navigate = useNavigate()

  useEffect(() => {
    const token = localStorage.getItem('zeropay_token')
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
    <div style={{
      display: 'flex', flexDirection: 'column', alignItems: 'center',
      justifyContent: 'center', minHeight: '100vh',
      background: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
      color: '#fff',
    }}>
      <div style={{ fontSize: '64px', marginBottom: '16px', animation: 'pulse 1.5s infinite' }}>💳</div>
      <h1 style={{ fontSize: '2.5rem', fontWeight: 800, margin: 0, letterSpacing: '-1px' }}>ZeroPay</h1>
      <p style={{ color: 'rgba(255,255,255,0.7)', marginTop: '8px' }}>Fast, secure mobile payments</p>
      <style>{`@keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }`}</style>
    </div>
  )
}
