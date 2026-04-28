import { useEffect, useState } from 'react'

interface User {
  id: number
  name: string
  email: string
}

export default function Dashboard() {
  const [user, setUser] = useState<User | null>(null)

  useEffect(() => {
    const raw = localStorage.getItem('user')
    if (raw) {
      try {
        setUser(JSON.parse(raw) as User)
      } catch {
        // ignore
      }
    }
  }, [])

  return (
    <div style={{ padding: '24px 16px', maxWidth: '600px', margin: '0 auto' }}>
      {/* Balance card */}
      <div style={balanceCardStyle}>
        <p style={{ color: 'rgba(255,255,255,0.7)', fontSize: '13px', margin: '0 0 4px' }}>
          Welcome back{user ? `, ${user.name.split(' ')[0]}` : ''}
        </p>
        <h2 style={{ color: '#fff', fontSize: '36px', fontWeight: 700, margin: '0 0 4px' }}>
          $0.00
        </h2>
        <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: '13px', margin: 0 }}>
          Available balance
        </p>
      </div>

      {/* Quick actions */}
      <div style={actionsRowStyle}>
        {QUICK_ACTIONS.map((a) => (
          <div key={a.label} style={actionItemStyle}>
            <div style={actionIconStyle}>{a.icon}</div>
            <span style={{ fontSize: '12px', color: '#444', fontWeight: 600 }}>{a.label}</span>
          </div>
        ))}
      </div>

      {/* Recent transactions placeholder */}
      <h3 style={{ fontSize: '16px', fontWeight: 700, color: '#1a1a2e', margin: '24px 0 12px' }}>
        Recent transactions
      </h3>
      <div style={emptyStyle}>
        <div style={{ fontSize: '40px', marginBottom: '8px' }}>💸</div>
        <p style={{ color: '#999', fontSize: '14px', margin: 0 }}>No transactions yet</p>
      </div>
    </div>
  )
}

const QUICK_ACTIONS = [
  { icon: '📤', label: 'Send' },
  { icon: '📥', label: 'Receive' },
  { icon: '📋', label: 'Request' },
  { icon: '🔔', label: 'Activity' },
]

const balanceCardStyle: React.CSSProperties = {
  background: 'linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%)',
  borderRadius: '16px',
  padding: '28px 24px',
  marginBottom: '20px',
}

const actionsRowStyle: React.CSSProperties = {
  display: 'flex',
  gap: '12px',
  justifyContent: 'space-between',
  marginBottom: '8px',
}

const actionItemStyle: React.CSSProperties = {
  flex: 1,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  gap: '6px',
  cursor: 'pointer',
}

const actionIconStyle: React.CSSProperties = {
  background: '#fff',
  borderRadius: '50%',
  width: '52px',
  height: '52px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  fontSize: '22px',
  boxShadow: '0 2px 8px rgba(0,0,0,0.08)',
}

const emptyStyle: React.CSSProperties = {
  textAlign: 'center',
  padding: '40px 16px',
  background: '#fff',
  borderRadius: '12px',
  boxShadow: '0 1px 8px rgba(0,0,0,0.05)',
}
