import type {
  EmailVerifierValues,
  SignInValues
} from '@/models/interfaces/auth/AuthenticationsValues'
import type { User } from '@/models/interfaces/auth/User'
import { apiClient } from '@/plugins/axios/api'
import type { AxiosResponse } from 'axios'

export class AuthenticationService {
  static async signIn(values: SignInValues): Promise<AxiosResponse> {
    return apiClient.post('/auth', JSON.stringify(values))
  }

  static async getAuthenticatedUser(): Promise<AxiosResponse<User>> {
    return apiClient.get('/api/users/me')
  }

  static async forgotPassword(email: string): Promise<AxiosResponse> {
    return apiClient.post('/api/forgot_password/', { email: email })
  }

  static async checkResetPasswordToken(token: string): Promise<AxiosResponse> {
    return apiClient.get(`/api/forgot_password/${token}`)
  }

  static async resetPassword(token: string, password: string): Promise<AxiosResponse> {
    return apiClient.post(`/api/forgot_password/${token}`, { password: password })
  }

  static async verifyEmail(emailVerifierValues: EmailVerifierValues): Promise<AxiosResponse> {
    return apiClient.post('/api/users/verify_email/verify', emailVerifierValues)
  }

  static async resendEmailVerifier(email: string): Promise<AxiosResponse> {
    return apiClient.post(`/api/users/verify_email/send`, { email: email })
  }

  static async refreshAuthToken(): Promise<AxiosResponse> {
    return apiClient.post('/api/token/refresh', {}, { withCredentials: true })
  }
}
