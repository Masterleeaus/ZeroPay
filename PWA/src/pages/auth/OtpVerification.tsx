import { useState, useRef, KeyboardEvent, ClipboardEvent } from 'react'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

const OTP_LENGTH = 6

export default function OtpVerification() {
  const [searchParams] = useSearchParams()
  const otpType = searchParams.get('type') ?? 'sms'
  const navigate = useNavigate()

  const [digits, setDigits] = useState<string[]>(Array(OTP_LENGTH).fill(''))
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const inputRefs = useRef<(HTMLInputElement | null)[]>([])

  const otp = digits.join('')

  const handleChange = (index: number, value: string) => {
    if (!/^\d?$/.test(value)) return
    const next = [...digits]
    next[index] = value
    setDigits(next)
    if (value && index < OTP_LENGTH - 1) {
      inputRefs.current[index + 1]?.focus()
    }
  }

  const handleKeyDown = (index: number, e: KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Backspace' && !digits[index] && index > 0) {
      inputRefs.current[index - 1]?.focus()
    }
  }

  const handlePaste = (e: ClipboardEvent<HTMLInputElement>) => {
    e.preventDefault()
    const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, OTP_LENGTH)
    const next = [...digits]
    pasted.split('').forEach((ch, i) => { next[i] = ch })
    setDigits(next)
    const lastFilled = Math.min(pasted.length, OTP_LENGTH - 1)
    inputRefs.current[lastFilled]?.focus()
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (otp.length < OTP_LENGTH) {
      setError('Please enter all 6 digits.')
      return
    }
    setLoading(true)
    setError('')
    try {
      await authApi.verifyOtp(otp, otpType)
      navigate('/auth/kyc', { replace: true })
    } catch (err: unknown) {
      const message =
        err instanceof Error ? err.message : 'Invalid or expired code. Please try again.'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  const label = otpType === 'email' ? 'email' : 'SMS'

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <div style={{ textAlign: 'center', marginBottom: '28px' }}>
          <div style={{ fontSize: '48px' }}>🔐</div>
          <h1 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', margin: '8px 0 4px' }}>
            Enter verification code
          </h1>
          <p style={{ color: '#666', fontSize: '14px' }}>
            We sent a 6-digit code via {label}. It expires in 10 minutes.
          </p>
        </div>

        {error && <div style={errorStyle}>{error}</div>}

        <form onSubmit={handleSubmit}>
          <div style={{ display: 'flex', gap: '8px', justifyContent: 'center', marginBottom: '24px' }}>
            {digits.map((digit, i) => (
              <input
                key={i}
                ref={(el) => { inputRefs.current[i] = el }}
                type="text"
                inputMode="numeric"
                maxLength={1}
                value={digit}
                onChange={(e) => handleChange(i, e.target.value)}
                onKeyDown={(e) => handleKeyDown(i, e)}
                onPaste={handlePaste}
                aria-label={`Digit ${i + 1}`}
                style={{
                  width: '44px',
                  height: '52px',
                  textAlign: 'center',
                  fontSize: '22px',
                  fontWeight: 700,
                  border: `2px solid ${digit ? '#e94560' : '#ddd'}`,
                  borderRadius: '10px',
                  outline: 'none',
                  color: '#1a1a2e',
                  transition: 'border-color 0.2s',
                }}
              />
            ))}
          </div>

          <button type="submit" disabled={loading || otp.length < OTP_LENGTH} style={btnStyle}>
            {loading ? 'Verifying…' : 'Verify Code'}
          </button>
        </form>

        <p style={{ textAlign: 'center', marginTop: '20px', fontSize: '14px', color: '#666' }}>
          Didn&apos;t receive it?{' '}
          <button
            type="button"
            style={{ background: 'none', border: 'none', color: '#e94560', cursor: 'pointer', fontWeight: 600, fontSize: '14px', padding: 0 }}
            onClick={() => navigate('/auth/verify-email')}
          >
            Resend
          </button>
        </p>

        <p style={{ textAlign: 'center', marginTop: '8px', fontSize: '14px' }}>
          <Link to="/auth/login" style={linkStyle}>← Back to Sign In</Link>
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

const linkStyle: React.CSSProperties = {
  color: '#e94560',
  textDecoration: 'none',
  fontWeight: 600,
}
