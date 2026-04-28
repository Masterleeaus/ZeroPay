import { useState } from 'react'
import { Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

type Step = 'form' | 'success'

export default function ForgotPassword() {
  const [email, setEmail] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const [step, setStep] = useState<Step>('form')

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    try {
      await authApi.forgotPassword(email)
      setStep('success')
    } catch (err: unknown) {
      const message =
        err instanceof Error ? err.message : 'Something went wrong. Please try again.'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        {step === 'success' ? (
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontSize: '56px', marginBottom: '16px' }}>📬</div>
            <h2 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', marginBottom: '12px' }}>
              Check your inbox
            </h2>
            <p style={{ color: '#666', fontSize: '15px', lineHeight: 1.5 }}>
              We&apos;ve sent a password reset link to{' '}
              <strong style={{ color: '#1a1a2e' }}>{email}</strong>.
              Please check your spam folder if it doesn&apos;t arrive within a few minutes.
            </p>
            <Link to="/auth/login" style={backLinkStyle}>← Back to Sign In</Link>
          </div>
        ) : (
          <>
            <div style={{ textAlign: 'center', marginBottom: '28px' }}>
              <div style={{ fontSize: '48px' }}>🔑</div>
              <h1 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', margin: '8px 0 4px' }}>
                Forgot password?
              </h1>
              <p style={{ color: '#666', fontSize: '14px', margin: 0 }}>
                Enter your email and we&apos;ll send you a reset link.
              </p>
            </div>

            {error && <div style={errorStyle}>{error}</div>}

            <form onSubmit={handleSubmit}>
              <div style={fieldStyle}>
                <label htmlFor="email" style={labelStyle}>Email address</label>
                <input
                  id="email"
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  autoComplete="email"
                  placeholder="you@example.com"
                  style={inputStyle}
                />
              </div>

              <button type="submit" disabled={loading} style={btnStyle}>
                {loading ? 'Sending…' : 'Send Reset Link'}
              </button>
            </form>

            <p style={{ textAlign: 'center', marginTop: '20px', fontSize: '14px' }}>
              <Link to="/auth/login" style={backLinkStyle}>← Back to Sign In</Link>
            </p>
          </>
        )}
      </div>
    </div>
  )
}

const pageStyle: React.CSSProperties = {
  minHeight: '100vh',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  background: '#f5f5f5',
  padding: '16px',
}

const cardStyle: React.CSSProperties = {
  background: '#fff',
  borderRadius: '16px',
  padding: '32px 24px',
  width: '100%',
  maxWidth: '400px',
  boxShadow: '0 2px 24px rgba(0,0,0,0.08)',
}

const fieldStyle: React.CSSProperties = { marginBottom: '20px' }

const labelStyle: React.CSSProperties = {
  display: 'block',
  fontWeight: 600,
  marginBottom: '6px',
  fontSize: '14px',
  color: '#1a1a2e',
}

const inputStyle: React.CSSProperties = {
  width: '100%',
  border: '1px solid #ddd',
  borderRadius: '8px',
  padding: '12px',
  fontSize: '15px',
  outline: 'none',
  boxSizing: 'border-box',
  color: '#1a1a2e',
}

const btnStyle: React.CSSProperties = {
  width: '100%',
  background: '#e94560',
  color: '#fff',
  border: 'none',
  borderRadius: '10px',
  padding: '14px',
  fontSize: '16px',
  fontWeight: 600,
  cursor: 'pointer',
}

const errorStyle: React.CSSProperties = {
  background: '#fff0f0',
  border: '1px solid #ffcccc',
  borderRadius: '8px',
  padding: '10px 14px',
  color: '#cc0000',
  fontSize: '14px',
  marginBottom: '16px',
}

const backLinkStyle: React.CSSProperties = {
  color: '#e94560',
  textDecoration: 'none',
  fontWeight: 600,
  fontSize: '14px',
}
