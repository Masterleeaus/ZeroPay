import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Dashboard from './pages/Dashboard';
import Login from './pages/Login';
import SendMoney from './pages/SendMoney';
import RequestMoney from './pages/RequestMoney';
import PaymentQR from './pages/PaymentQR';
import TransactionHistory from './pages/TransactionHistory';
import Notifications from './pages/Notifications';
import Profile from './pages/Profile';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Navigate to="/dashboard" replace />} />
        <Route path="/login" element={<Login />} />
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/send" element={<SendMoney />} />
        <Route path="/request" element={<RequestMoney />} />
        <Route path="/qr" element={<PaymentQR />} />
        <Route path="/history" element={<TransactionHistory />} />
        <Route path="/notifications" element={<Notifications />} />
        <Route path="/profile" element={<Profile />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
