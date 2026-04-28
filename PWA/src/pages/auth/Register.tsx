import { useState } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

export default function Register() {
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')
  const [confirm, setConfirm] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (password !== confirm) {
      setError('Passwords do not match.')
      return
    }
    setLoading(true)
    setError('')
    try {
      const res = await authApi.register({
        name,
        email,
        password,
        password_confirmation: confirm,
        phone: phone || undefined,
      })
      // Store token and proceed to email verification
      localStorage.setItem('auth_token', res.data.token)
      localStorage.setItem('user', JSON.stringify(res.data.user))
      navigate('/auth/verify-email', { replace: true })
    } catch (err: unknown) {
      const message =
        err instanceof Error ? err.message : 'Registration failed. Please try again.'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <div style={{ textAlign: 'center', marginBottom: '28px' }}>
          <div style={{ fontSize: '48px' }}>💳</div>
          <h1 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', margin: '8px 0 4px' }}>
            Create account
          </h1>
          <p style={{ color: '#666', fontSize: '14px', margin: 0 }}>Join ZeroPay in seconds</p>
        </div>

        {error && <div style={errorStyle}>{error}</div>}

        <form onSubmit={handleSubmit}>
          <div style={fieldStyle}>
            <label htmlFor="name" style={labelStyle}>Full name</label>
            <input
              id="name"
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
              autoComplete="name"
              placeholder="Jane Smith"
              style={inputStyle}
            />
          </div>

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

          <div style={fieldStyle}>
            <label htmlFor="phone" style={labelStyle}>
              Phone number{' '}
              <span style={{ fontWeight: 400, color: '#999' }}>(optional)</span>
            </label>
            <input
              id="phone"
              type="tel"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              autoComplete="tel"
              placeholder="+61 4XX XXX XXX"
              style={inputStyle}
            />
          </div>

          <div style={fieldStyle}>
            <label htmlFor="password" style={labelStyle}>Password</label>
            <input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              autoComplete="new-password"
              placeholder="Minimum 8 characters"
              minLength={8}
              style={inputStyle}
            />
          </div>

          <div style={fieldStyle}>
            <label htmlFor="confirm" style={labelStyle}>Confirm password</label>
            <input
              id="confirm"
              type="password"
              value={confirm}
              onChange={(e) => setConfirm(e.target.value)}
              required
              autoComplete="new-password"
              placeholder="Repeat password"
              style={inputStyle}
            />
          </div>

          <button type="submit" disabled={loading} style={btnStyle}>
            {loading ? 'Creating account…' : 'Create Account'}
          </button>
        </form>

        <p style={{ textAlign: 'center', marginTop: '20px', color: '#666', fontSize: '14px' }}>
          Already have an account?{' '}
          <Link to="/auth/login" style={{ color: '#e94560', textDecoration: 'none', fontWeight: 600 }}>
            Sign in
          </Link>
        </p>
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

const fieldStyle: React.CSSProperties = { marginBottom: '16px' }

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
  marginTop: '4px',
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
