import type { SignInValues, SignUpValues } from "@/models/interfaces/auth/AuthenticationsValues";
import type { User } from "@/models/interfaces/auth/User";
import { apiClient } from '@/assets/plugins/axios'
import type { AxiosResponse } from "axios";


export class AuthenticationService {
    static async signIn(values: SignInValues): Promise<AxiosResponse> {
        return apiClient.post('/auth', JSON.stringify(values), {
            headers: { 'Content-Type': 'application/json' }
        })
    }

    static async getAuthenticatedUser(): Promise<AxiosResponse<User>> {
        return apiClient.get('/api/users/me', { headers:  {'accept': 'application/ld+json'}})
    }
}
