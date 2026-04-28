import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { walletApi, type WalletBalance } from '../../api/wallet'
import { transactionsApi, type Transaction } from '../../api/transactions'
import { usePushNotifications } from '../../hooks/usePushNotifications'

const quickActions = [
  { label: '📷 Make Payment', to: '/pay/scan' },
  { label: '💸 Request $', to: '/request' },
  { label: '📥 Receive', to: '/receive' },
  { label: '🔗 Pay Links', to: '/links' },
]

function statusColor(s: string) {
  if (s === 'completed') return '#22c55e'
  if (s === 'failed') return '#ef4444'
  return '#f59e0b'
}

export default function Dashboard() {
  const [balance, setBalance] = useState<WalletBalance | null>(null)
  const [transactions, setTransactions] = useState<Transaction[]>([])
  const [loading, setLoading] = useState(true)
  const navigate = useNavigate()
  const { permission, subscribe } = usePushNotifications()

  const load = async () => {
    try {
      const [bal, txs] = await Promise.all([
        walletApi.getBalance(),
        transactionsApi.list({ page: 1 }),
      ])
      setBalance(bal.data)
      setTransactions(txs.data.data.slice(0, 5))
    } catch {
      // handled gracefully
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    void load()
    if (permission === 'default') {
      setTimeout(() => void subscribe(), 3000)
    }
  }, [])

  const user = (() => {
    try { return JSON.parse(localStorage.getItem('user') ?? '{}') as { name?: string } }
    catch { return {} }
  })()

  return (
    <div style={{ padding: '16px', maxWidth: '480px', margin: '0 auto' }}>
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <p style={{ margin: 0, color: '#666', fontSize: '13px' }}>Welcome back</p>
          <h2 style={{ margin: 0, fontSize: '1.3rem', color: '#1a1a2e' }}>{user.name ?? 'User'}</h2>
        </div>
        <button onClick={() => navigate('/notifications')} style={{ background: 'none', border: 'none', fontSize: '24px' }}>🔔</button>
      </div>

      {/* Balance card */}
      <div style={{ background: 'linear-gradient(135deg, #1a1a2e, #0f3460)', color: '#fff', borderRadius: '16px', padding: '24px', marginBottom: '24px' }}>
        <p style={{ margin: '0 0 8px', opacity: 0.7, fontSize: '13px' }}>Wallet Balance</p>
        {loading
          ? <div style={{ height: '40px', background: 'rgba(255,255,255,0.1)', borderRadius: '8px' }} />
          : <h1 style={{ margin: 0, fontSize: '2.2rem', fontWeight: 800 }}>
              {balance ? balance.formatted : '—'}
            </h1>
        }
      </div>

      {/* Quick actions */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', marginBottom: '24px' }}>
        {quickActions.map(({ label, to }) => (
          <button key={to} onClick={() => navigate(to)} style={{
            background: '#fff', border: '1.5px solid #e0e0e0', borderRadius: '12px',
            padding: '16px', fontSize: '14px', fontWeight: 600, color: '#1a1a2e',
            cursor: 'pointer', textAlign: 'left',
          }}>
            {label}
          </button>
        ))}
      </div>

      {/* Recent transactions */}
      <div>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
          <h3 style={{ margin: 0, fontSize: '1rem', color: '#1a1a2e' }}>Recent Transactions</h3>
          <button onClick={() => navigate('/transactions')} style={{ background: 'none', border: 'none', color: '#e94560', fontSize: '13px', cursor: 'pointer' }}>
            See all
          </button>
        </div>
        {loading ? (
          <p style={{ color: '#666', textAlign: 'center' }}>Loading…</p>
        ) : transactions.length === 0 ? (
          <p style={{ color: '#666', textAlign: 'center' }}>No transactions yet</p>
        ) : (
          transactions.map(tx => (
            <div key={tx.id} onClick={() => navigate(`/transactions/${tx.id}`)} style={{
              display: 'flex', justifyContent: 'space-between', alignItems: 'center',
              padding: '12px 0', borderBottom: '1px solid #f0f0f0', cursor: 'pointer',
            }}>
              <div>
                <p style={{ margin: 0, fontWeight: 600, fontSize: '14px' }}>{tx.merchant_name ?? tx.reference ?? 'Payment'}</p>
                <p style={{ margin: '2px 0 0', color: '#666', fontSize: '12px' }}>{tx.gateway} · {new Date(tx.created_at).toLocaleDateString()}</p>
              </div>
              <div style={{ textAlign: 'right' }}>
                <p style={{ margin: 0, fontWeight: 700, color: tx.direction === 'received' ? '#22c55e' : '#1a1a2e' }}>
                  {tx.direction === 'received' ? '+' : '-'}{tx.currency} {tx.amount.toFixed(2)}
                </p>
                <span style={{ fontSize: '11px', color: statusColor(tx.status), fontWeight: 600 }}>{tx.status}</span>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  )
}
