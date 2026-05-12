import client from './client'

export interface LoginPayload { email: string; password: string }
export interface RegisterPayload { name: string; email: string; password: string; password_confirmation: string; phone?: string }
export interface AuthResponse { token: string; user: { id: number; name: string; email: string } }

export const authApi = {
  login: (data: LoginPayload) => client.post<AuthResponse>('/auth/login', data),
  register: (data: RegisterPayload) => client.post<AuthResponse>('/auth/register', data),
  forgotPassword: (email: string) => client.post('/auth/forgot-password', { email }),
  verifyEmail: (token: string) => client.post('/auth/verify-email', { token }),
  verifyOtp: (otp: string, type: string) => client.post('/auth/verify-otp', { otp, type }),
  logout: () => client.post('/auth/logout'),
}
