import { apiClient } from "@/assets/plugins/axios";
import type { LikesList } from "@/models/interfaces/LikesList";
import type { AxiosResponse } from "axios";

export class LikeService {

    static async getLikesList(): Promise<LikesList>
    {
        return (await apiClient.get('/api/user_likes', { headers:  {'accept': 'application/ld+json'}})).data as LikesList
    }

    static async addLike(id: string): Promise<AxiosResponse> {
        return apiClient.post('/api/user_likes', JSON.stringify({
            "userId": "/api/users/me",
            "contentId": id
        }), { headers: { 'Content-Type': 'application/ld+json' } })
    }

    static async deleteLike(id: number): Promise<AxiosResponse> {
        return apiClient.delete(`/api/user_likes/${id}`)
    }
}