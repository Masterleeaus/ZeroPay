import { useState } from 'react'
import { sessionsApi, type QrPayload } from '../../api/sessions'
import { cacheSession } from '../../db'
import { useOnlineStatus } from '../../hooks/useOnlineStatus'
import QRCode from 'qrcode'

export default function RequestMoney() {
  const [amount, setAmount] = useState('')
  const [currency, setCurrency] = useState('AUD')
  const [reference, setReference] = useState('')
  const [qrDataUrl, setQrDataUrl] = useState('')
  const [session, setSession] = useState<{ token: string; payload: QrPayload } | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [copied, setCopied] = useState(false)
  const online = useOnlineStatus()

  const createSession = async () => {
    setLoading(true)
    setError('')
    try {
      const res = await sessionsApi.create({
        amount: amount ? parseFloat(amount) : undefined,
        currency,
        reference: reference || undefined,
      })
      const { session_token, qr_payload } = res.data
      const qrPayload = JSON.stringify(qr_payload)
      const dataUrl = await QRCode.toDataURL(qrPayload, { width: 300, margin: 2 })
      setQrDataUrl(dataUrl)
      setSession({ token: session_token, payload: qr_payload })
      await cacheSession({
        session_token,
        qr_payload: qr_payload as unknown as Record<string, unknown>,
        expires_at: qr_payload.expiry_timestamp,
      })
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Failed to create session'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  const shareLink = async () => {
    if (!session) return
    const url = `${window.location.origin}/pay/session/${session.token}`
    if (navigator.share) {
      await navigator.share({ title: 'ZeroPay Request', text: 'Pay me via ZeroPay', url })
    } else {
      await navigator.clipboard.writeText(url)
      setCopied(true)
      setTimeout(() => setCopied(false), 2000)
    }
  }

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>💸 Get Paid</h2>

      {!session ? (
        <div>
          <div style={{ marginBottom: '14px' }}>
            <label style={labelStyle}>Amount (optional)</label>
            <div style={{ display: 'flex', gap: '8px' }}>
              <select value={currency} onChange={e => setCurrency(e.target.value)} style={{ padding: '12px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px' }}>
                {['AUD', 'USD', 'GBP', 'EUR'].map(c => <option key={c}>{c}</option>)}
              </select>
              <input style={inputStyle} type="number" min="0" step="0.01" value={amount} onChange={e => setAmount(e.target.value)} placeholder="0.00" />
            </div>
          </div>
          <div style={{ marginBottom: '20px' }}>
            <label style={labelStyle}>Reference (optional)</label>
            <input style={inputStyle} type="text" value={reference} onChange={e => setReference(e.target.value)} placeholder="INV-001" />
          </div>
          {error && <div style={{ background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>{error}</div>}
          <button onClick={() => void createSession()} disabled={loading || !online} style={btnStyle}>
            {loading ? 'Creating QR…' : '🔳 Generate QR Code'}
          </button>
          {!online && <p style={{ color: '#f59e0b', fontSize: '13px', textAlign: 'center' }}>⚠️ Connect to internet to generate a payment QR</p>}
        </div>
      ) : (
        <div style={{ textAlign: 'center' }}>
          {qrDataUrl && <img src={qrDataUrl} alt="Payment QR" style={{ width: '100%', maxWidth: '300px', margin: '0 auto 16px', display: 'block', borderRadius: '12px' }} />}
          <p style={{ color: '#666', fontSize: '13px', marginBottom: '16px' }}>
            Share this QR or link for payment
          </p>
          <div style={{ display: 'flex', gap: '10px', justifyContent: 'center' }}>
            <button onClick={() => void shareLink()} style={{ ...btnStyle, background: '#0f3460', flex: 1 }}>
              {copied ? '✅ Copied!' : '🔗 Share Link'}
            </button>
            <button onClick={() => { setSession(null); setQrDataUrl('') }} style={{ ...btnStyle, background: '#e0e0e0', color: '#1a1a2e', flex: 1 }}>
              New QR
            </button>
          </div>
        </div>
      )}
    </div>
  )
}

const labelStyle: React.CSSProperties = { display: 'block', fontWeight: 600, marginBottom: '6px', fontSize: '14px', color: '#1a1a2e' }
const inputStyle: React.CSSProperties = { flex: 1, width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', outline: 'none', boxSizing: 'border-box' }
const btnStyle: React.CSSProperties = { width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '15px', fontWeight: 600, cursor: 'pointer' }
