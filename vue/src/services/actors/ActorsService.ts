import type { Actor, ActorSubmission } from '@/models/interfaces/Actor'
import { apiClient } from '@/plugins/axios/api'
import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { AdministrativeScope } from '@/models/interfaces/AdministrativeScope'
import FileUploader from '@/services/files/FileUploader'
import type { ActorMediaObject } from '@/models/interfaces/object/MediaObject'

export class ActorsService {
  static async getActors(): Promise<Actor[]> {
    const data = (
      await apiClient.get('/api/actors', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member'] as Actor[]
  }

  static async getAll(): Promise<Actor[]> {
    return (await apiClient.get('/api/actors/all')).data['hydra:member']
  }

  static async getActor(id: string): Promise<Actor> {
    const data = (
      await apiClient.get(`/api/actors/${id}`, { headers: { accept: 'application/ld+json' } })
    ).data
    return data as Actor
  }

  static async createOrEditActor(
    actor: ActorSubmission,
    edit: boolean,
    id?: string | undefined
  ): Promise<Actor> {
    if (actor.logoToUpload) {
      const newLogo = await FileUploader.uploadMedia(actor.logoToUpload.file)
      actor.logo = newLogo['@id']
    }
    const newImagesLoaded = await Promise.all(
      actor.imagesToUpload.map(async (img) => await FileUploader.uploadMedia(img.file))
    )
    if (actor.images && actor.images.length > 0) {
      actor.images.push(...(newImagesLoaded as ActorMediaObject[]))
    } else {
      actor.images = []
    }
    actor = this.transformSymfonyRelationToIRIs(actor)
    let data
    if (!edit) {
      data = (
        await apiClient.post('/api/actors', actor, {
          headers: {
            'Content-Type': 'application/ld+json',
            Accept: 'application/ld+json'
          }
        })
      ).data
    } else {
      data = (
        await apiClient.patch(`/api/actors/${id}`, actor, {
          headers: {
            'Content-Type': 'application/merge-patch+json',
            Accept: 'application/ld+json'
          }
        })
      ).data
    }

    return data as Actor
  }

  static async deleteActor(id: string): Promise<void> {
    await apiClient.delete(`/api/actors/${id}`)
  }

  static async getActorsExpertisesList(): Promise<ActorExpertise[]> {
    const data = (
      await apiClient.get('/api/actor_expertises', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member']
  }
  static async getActorsThematicsList(): Promise<Thematic[]> {
    const data = (
      await apiClient.get('/api/thematics', { headers: { accept: 'application/ld+json' } })
    ).data
    return data['hydra:member']
  }
  static async getActorsAdministrativesScopesList(): Promise<AdministrativeScope[]> {
    const data = (
      await apiClient.get('/api/administrative_scopes', {
        headers: { accept: 'application/ld+json' }
      })
    ).data
    return data['hydra:member']
  }

  static transformSymfonyRelationToIRIs(actor: any): ActorSubmission {
    for (const key in actor) {
      if (Array.isArray(actor[key]) && actor[key][0]?.['@id']) {
        actor[key] = (actor[key] as SymfonyRelation[]).map(
          (x: SymfonyRelation) => x['@id']
        ) as never
      }
    }
    return actor
  }
}
