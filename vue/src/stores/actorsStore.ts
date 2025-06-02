import { StoresList } from '@/models/enums/app/StoresList'
import type { Actor, ActorSubmission } from '@/models/interfaces/Actor'
import { ActorsService } from '@/services/actors/ActorsService'
import { defineStore } from 'pinia'
import { reactive, ref, watch, type Reactive, type Ref } from 'vue'
import { useApplicationStore } from './applicationStore'
import { useRouter } from 'vue-router'
import type { ActorExpertise } from '@/models/interfaces/ActorExpertise'
import type { Thematic } from '@/models/interfaces/Thematic'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { i18n } from '@/plugins/i18n'
import { useUserStore } from '@/stores/userStore'
import { ItemType } from '@/models/enums/app/ItemType'
import { DialogKey } from '@/models/enums/app/DialogKey'

export const useActorsStore = defineStore(StoresList.ACTORS, () => {
  const dataLoaded = ref(false)
  const router = useRouter()
  const actors: Actor[] = reactive([])
  const actorsList: Ref<Partial<Actor>[]> = ref([])
  const selectedActor: Ref<Actor | null> = ref(null)
  const actorForSubmission: Ref<ActorSubmission | null> = ref(null)
  const actorsExpertises: ActorExpertise[] = reactive([])
  const actorsThematics: Thematic[] = reactive([])
  const actorEdition: Reactive<{ active: boolean; actor: Actor | null }> = reactive({
    active: false,
    actor: null
  })

  async function getActors(): Promise<void> {
    actors.splice(0, actors.length)
    actors.push(...(await ActorsService.getActors()))
    dataLoaded.value = true
  }

  async function getAll(): Promise<void> {
    if (actorsList.value.length === 0) {
      actorsList.value = await ActorsService.getAll()
    }
  }

  async function getActorsSelectListContent(): Promise<void> {
    actorsExpertises.push(...(await ActorsService.getActorsExpertisesList()))
  }

  async function setSelectedActor(id: string, redirect: boolean = true) {
    selectedActor.value = await ActorsService.getActor(id)
    if (redirect) {
      const appStore = useApplicationStore()
      appStore.currentContentPage = ContentPagesList.ACTOR
      router.push({ name: 'actorProfile', params: { slug: selectedActor.value.slug } })
    }
  }

  watch(
    () => actorEdition.active,
    () => {
      if (!actorEdition.active) {
        useApplicationStore().showEditContentDialog = false
      }
    }
  )

  function setActorEditionMode(actor: Actor | null) {
    actorEdition.actor = actor
    actorEdition.active = true
    useApplicationStore().showEditContentDialog = true
  }

  async function createOrEditActor(actor: ActorSubmission, edit: boolean) {
    try {
      actorForSubmission.value = actor
      if (edit || useUserStore().userIsAdmin()) {
        await saveActor(actor, edit)
      } else {
        actorEdition.active = false
        useApplicationStore().activeDialog = DialogKey.EDITOR_NEW_MESSAGE
        useApplicationStore().showEditMessageDialog = ItemType.ACTOR
      }
    } catch (error) {
      addNotification(i18n.t('actors.form.submitError'), NotificationType.ERROR, error as string)
    }
  }

  function resetActorEditionMode() {
    actorEdition.actor = null
    actorEdition.active = false
    useApplicationStore().showEditContentDialog = false
    useApplicationStore().showEditMessageDialog = false
    actorForSubmission.value = null
  }

  async function saveActor(actor: ActorSubmission, edit: boolean) {
    const result = await ActorsService.createOrEditActor(actor, edit)
    await getActors()
    if (result.isValidated || useUserStore().userIsAdmin()) {
      selectedActor.value = await ActorsService.getActor(result.id)
    }
    resetActorEditionMode()
    addNotification(
      edit ? i18n.t('actors.form.submitEdit') : i18n.t('actors.form.submitSuccess'),
      NotificationType.SUCCESS
    )
  }

  async function deleteActor(id: string) {
    await ActorsService.deleteActor(id)
    await getActors()
    addNotification(i18n.t('actors.form.delete'), NotificationType.SUCCESS)
  }

  const updateActor = (updatedActor: Actor) => {
    actors.forEach((actor, key) => {
      if (actor.id === updatedActor.id) {
        actors[key] = {
          ...actor,
          ...updatedActor
        }
      }
    })
    if (selectedActor.value && selectedActor.value.id === updatedActor.id) {
      selectedActor.value = {
        ...selectedActor.value,
        ...updatedActor
      }
    }
  }

  return {
    dataLoaded,
    actors,
    actorsExpertises,
    actorsThematics,
    selectedActor,
    actorEdition,
    actorForSubmission,
    actorsList,
    getActors,
    getAll,
    getActorsSelectListContent,
    setSelectedActor,
    setActorEditionMode,
    resetActorEditionMode,
    createOrEditActor,
    saveActor,
    deleteActor,
    updateActor
  }
})
