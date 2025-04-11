import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, type Ref, computed, watch } from 'vue'
import type { Resource, ResourceEvent } from '@/models/interfaces/Resource'
import { ResourceService } from '@/services/resources/ResourceService'
import { FormType } from '@/models/enums/app/FormType'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { useUserStore } from '@/stores/userStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { ItemType } from '@/models/enums/app/ItemType'

export const useResourceStore = defineStore(StoresList.RESOURCES, () => {
  const resources: Ref<Resource[]> = ref([])
  const resource: Ref<Resource | null> = ref(null)
  const resourceForSubmission: Ref<Resource | null> = ref(null)
  const nearestEvents: Ref<ResourceEvent[]> = ref([])
  const editedResourceId: Ref<Resource['id'] | null> = ref(null)
  const isResourceFormShown = ref(false)

  const editedResource = computed(() => {
    return resources.value.find((resource) => resource.id === editedResourceId.value)
  })

  async function getAll(): Promise<void> {
    if (resources.value.length <= 3) {
      resources.value = await ResourceService.getAll()
    }
  }
  const getNearestEvents = async () => {
    if (nearestEvents.value.length > 0) return
    nearestEvents.value = await ResourceService.getNearestEvents()
    if (resources.value.length === 0) {
      resources.value = nearestEvents.value
    }
  }

  const submitResource = async (resource: Resource, type: FormType) => {
    if (type !== FormType.CREATE || useUserStore().userIsAdmin()) {
      return await saveResource(resource, type)
    }
    resourceForSubmission.value = resource
    isResourceFormShown.value = false
    useApplicationStore().activeDialog = DialogKey.EDITOR_NEW_MESSAGE
    useApplicationStore().showEditMessageDialog = ItemType.RESOURCE
  }

  const saveResource = async (resource: Resource, type: FormType) => {
    const submittedResource =
      type === FormType.CREATE
        ? await ResourceService.post(resource)
        : await ResourceService.patch(resource)
    if (type === FormType.CREATE && useUserStore().userIsAdmin()) {
      resources.value.push(submittedResource)
    } else if (type === FormType.EDIT || type === FormType.VALIDATE) {
      updateResource(submittedResource)
    }
    addNotification(i18n.t(`notifications.resource.${type}`), NotificationType.SUCCESS)
    return submittedResource
  }

  const updateResource = (updatedResource: Resource) => {
    resources.value.forEach((resource, key) => {
      if (resource.id === updatedResource.id) {
        resources.value[key] = updatedResource
      }
    })
  }

  watch(
    () => isResourceFormShown.value,
    (newValue) => {
      if (newValue == false) {
        editedResourceId.value = null
      }
    }
  )

  const deleteResource = async (resource: Resource) => {
    await ResourceService.delete(resource)
    resources.value.forEach((p, key) => {
      if (p.id === resource.id) {
        resources.value.splice(key, 1)
        addNotification(i18n.t('notifications.resource.delete'), NotificationType.SUCCESS)
      }
    })
  }

  return {
    resources,
    resource,
    resourceForSubmission,
    isResourceFormShown,
    editedResourceId,
    editedResource,
    nearestEvents,
    getAll,
    submitResource,
    saveResource,
    deleteResource,
    updateResource,
    getNearestEvents
  }
})
