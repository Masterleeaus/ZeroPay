import { Outlet, NavLink, useNavigate } from 'react-router-dom'
import { authApi } from '../api/auth'

const NAV_ITEMS = [
  { to: '/dashboard', label: '🏠', title: 'Home' },
]

export default function Layout() {
  const navigate = useNavigate()

  const handleLogout = async () => {
    try {
      await authApi.logout()
    } catch {
      // best-effort
    } finally {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      navigate('/auth/login', { replace: true })
    }
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      {/* Top bar */}
      <header style={headerStyle}>
        <span style={{ fontWeight: 700, fontSize: '18px', color: '#fff' }}>💳 ZeroPay</span>
        <button onClick={handleLogout} style={logoutBtnStyle}>Sign out</button>
      </header>

      {/* Page content */}
      <main style={{ flex: 1, background: '#f5f5f5' }}>
        <Outlet />
      </main>

      {/* Bottom nav */}
      <nav style={navStyle}>
        {NAV_ITEMS.map((item) => (
          <NavLink
            key={item.to}
            to={item.to}
            title={item.title}
            style={({ isActive }) => ({
              ...navItemStyle,
              color: isActive ? '#e94560' : '#666',
            })}
          >
            <span style={{ fontSize: '22px' }}>{item.label}</span>
            <span style={{ fontSize: '11px', marginTop: '2px' }}>{item.title}</span>
          </NavLink>
        ))}
      </nav>
    </div>
  )
}

const headerStyle: React.CSSProperties = {
  background: '#1a1a2e',
  padding: '12px 20px',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'space-between',
}

const logoutBtnStyle: React.CSSProperties = {
  background: 'transparent',
  border: '1px solid rgba(255,255,255,0.3)',
  color: '#fff',
  borderRadius: '8px',
  padding: '6px 12px',
  cursor: 'pointer',
  fontSize: '13px',
}

const navStyle: React.CSSProperties = {
  display: 'flex',
  background: '#fff',
  borderTop: '1px solid #eee',
  padding: '8px 0',
}

const navItemStyle: React.CSSProperties = {
  flex: 1,
  display: 'flex',
  flexDirection: 'column',
  alignItems: 'center',
  textDecoration: 'none',
  padding: '4px 0',
}
