import { StoresList } from '@/models/enums/app/StoresList'
import type { Actor, ActorSubmission } from '@/models/interfaces/Actor'
import { ActorsService } from '@/services/actors/ActorsService'
import { defineStore } from 'pinia'
import { reactive, ref, watch, type Reactive, type Ref } from 'vue'
import { useApplicationStore } from './applicationStore'
import { useRouter } from 'vue-router'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type { Thematic } from '@/models/interfaces/Thematic'
import type { AdministrativeScope } from '@/models/interfaces/AdministrativeScope'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'


export const useActorsStore = defineStore(StoresList.ACTORS, () => {
  const dataLoaded = ref(false)
  const router = useRouter();
  const actors: Actor[] = reactive([])
  const selectedActor: Ref<Actor | null> = ref(null)
  const actorsExpertises: ActorExpertise[] = reactive([])
  const actorsThematics: Thematic[] = reactive([])
  const actorsAdministrativesScopes: AdministrativeScope[] = reactive([])

  async function getActors(): Promise<void> {
      actors.splice(0, actors.length)
      actors.push(...await ActorsService.getActors())
      dataLoaded.value = true
  }

  async function getActorsSelectListContent(): Promise<void> {
    actorsExpertises.push(...await ActorsService.getActorsExpertisesList())
    actorsAdministrativesScopes.push(...await ActorsService.getActorsAdministrativesScopesList())
}

  async function setSelectedActor(id: string, redirect: boolean = true) {
    selectedActor.value = await ActorsService.getActor(id)
    if (redirect) {
      const appStore = useApplicationStore()
      appStore.currentContentPage = ContentPagesList.ACTOR
      router.push('/actors/' + selectedActor.value.slug)
    }
  }

  const actorEdition: Reactive<{active: boolean, actor: Actor | null}> = reactive({
    active: false,
    actor: null
  })

  watch(() => actorEdition.active, () => {
    if (!actorEdition.active) {
      useApplicationStore().showEditContentDialog = false
    }
  })
  function setActorEditionMode(actor: Actor | null) {
    actorEdition.actor = actor
    actorEdition.active = true
    useApplicationStore().showEditContentDialog = true
  }

  async function createOrEditActor(actor: ActorSubmission, edit: boolean) {
    const id = selectedActor.value?.id
    const result = await ActorsService.createOrEditActor(actor, edit, id)
    await getActors()
    selectedActor.value = await ActorsService.getActor(result.id)
    useApplicationStore().showEditContentDialog = false
    useApplicationStore().snackBarMessage = edit ? 'L\'acteur a bien été modifié' : 'L\'acteur a bien été ajouté'
    useApplicationStore().showSnackBar = true
  }

  async function deleteActor(id: string) {
    await ActorsService.deleteActor(id)
    await getActors()
    useApplicationStore().snackBarMessage = 'L\'acteur a bien été supprimé'
    useApplicationStore().showSnackBar = true
  }

  return { 
    dataLoaded,
    actors,
    actorsExpertises,
    actorsThematics,
    actorsAdministrativesScopes,
    selectedActor,
    getActors,
    getActorsSelectListContent,
    setSelectedActor,
    actorEdition,
    setActorEditionMode,
    createOrEditActor,
    deleteActor
  }
})
