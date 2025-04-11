import type { Actor, ActorSubmission } from '@/models/interfaces/Actor'
import { apiClient } from '@/plugins/axios/api'
import type { SymfonyRelation } from '@/models/interfaces/SymfonyRelation'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type { Thematic } from '@/models/interfaces/Thematic'
import FileUploader from '@/services/files/FileUploader'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'
import { transformSymfonyRelationToIRIs } from '@/services/utils/UtilsService'

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

  static async post(actors: ActorSubmission): Promise<Actor> {
    return await apiClient.post('/api/actors', actors).then((response) => response.data)
  }

  static async patch(actors: Partial<ActorSubmission | Actor>): Promise<Actor> {
    return await apiClient
      .patch('/api/actors/' + actors.id, actors)
      .then((response) => response.data)
  }

  static async patchImages(actor: Actor): Promise<Actor> {
    return this.patch({
      images: actor.images,
      id: actor.id,
      logo: actor.logo
    })
  }

  static async createOrEditActor(actorToSubmit: ActorSubmission, edit: boolean): Promise<Actor> {
    let symfonyActor = transformSymfonyRelationToIRIs<Actor>(actorToSubmit)

    const actor = edit ? await this.patch(symfonyActor) : await this.post(actorToSubmit)

    if (!edit) {
      symfonyActor.id = actor.id
    }

    if (actorToSubmit.logoToUpload) {
      symfonyActor.logo = (await FileUploader.uploadMedia(
        actorToSubmit.logoToUpload.file
      )) as BaseMediaObject
    }

    const images = await Promise.all(
      actorToSubmit.imagesToUpload.map(async (img) => await FileUploader.uploadMedia(img.file))
    )

    if (images.length > 0) {
      symfonyActor.images.push(...(images as BaseMediaObject[]))
    } else if (actorToSubmit.images.length === 0) {
      symfonyActor.images = []
    }

    symfonyActor = transformSymfonyRelationToIRIs<Actor>(symfonyActor)

    if (symfonyActor.id && (images.length > 0 || actorToSubmit.logoToUpload)) {
      return await this.patchImages(symfonyActor)
    }

    return actor
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
