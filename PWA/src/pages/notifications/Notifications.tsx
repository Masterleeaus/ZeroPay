import { usePushNotifications } from '../../hooks/usePushNotifications'

const mockNotifications = [
  { id: 1, type: 'payment.completed', title: 'Payment Received', body: 'You received AUD 120.00 from Kate', time: '2 min ago', icon: '✅' },
  { id: 2, type: 'payment.pending', title: 'Payment Processing', body: 'Payment of AUD 50.00 is being processed', time: '5 min ago', icon: '⏳' },
  { id: 3, type: 'session.expiring', title: 'Session Expiring', body: 'Your payment QR expires in 5 minutes', time: '10 min ago', icon: '⏰' },
]

export default function Notifications() {
  const { permission, subscribed, subscribe } = usePushNotifications()

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>🔔 Notifications</h2>

      {permission === 'default' && (
        <div style={{ background: '#eff6ff', border: '1px solid #bfdbfe', borderRadius: '12px', padding: '16px', marginBottom: '20px' }}>
          <p style={{ margin: '0 0 12px', fontWeight: 600, color: '#1e40af' }}>Enable push notifications</p>
          <p style={{ margin: '0 0 12px', color: '#3b82f6', fontSize: '14px' }}>Get instant alerts when you receive payments.</p>
          <button onClick={() => void subscribe()} style={{ padding: '10px 20px', background: '#1e40af', color: '#fff', border: 'none', borderRadius: '8px', fontWeight: 600, cursor: 'pointer' }}>
            Enable Notifications
          </button>
        </div>
      )}

      {permission === 'denied' && (
        <div style={{ background: '#fee2e2', border: '1px solid #fca5a5', borderRadius: '12px', padding: '16px', marginBottom: '20px' }}>
          <p style={{ margin: 0, color: '#991b1b', fontSize: '14px' }}>Notifications are blocked. Enable them in browser settings.</p>
        </div>
      )}

      {subscribed && (
        <div style={{ background: '#dcfce7', border: '1px solid #86efac', borderRadius: '12px', padding: '12px 16px', marginBottom: '20px' }}>
          <p style={{ margin: 0, color: '#166534', fontSize: '14px' }}>✅ Push notifications enabled</p>
        </div>
      )}

      {mockNotifications.map(n => (
        <div key={n.id} style={{ display: 'flex', gap: '12px', alignItems: 'flex-start', padding: '14px', background: '#fff', borderRadius: '10px', boxShadow: '0 1px 4px rgba(0,0,0,0.06)', marginBottom: '8px' }}>
          <div style={{ fontSize: '24px', flexShrink: 0 }}>{n.icon}</div>
          <div style={{ flex: 1 }}>
            <p style={{ margin: '0 0 4px', fontWeight: 600, fontSize: '14px', color: '#1a1a2e' }}>{n.title}</p>
            <p style={{ margin: '0 0 6px', color: '#666', fontSize: '13px' }}>{n.body}</p>
            <p style={{ margin: 0, color: '#999', fontSize: '12px' }}>{n.time}</p>
          </div>
        </div>
      ))}
    </div>
  )
}
