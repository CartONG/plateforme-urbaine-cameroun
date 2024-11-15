import { apiClient } from "@/assets/plugins/axios";

export class UsersService {
    static async getMembers() {
        const data = (await apiClient.get('/api/users', { headers:  {'accept': 'application/ld+json'}})).data
        console.log(data)
        return data["hydra:member"]
    }
}