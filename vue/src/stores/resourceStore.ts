import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, type Ref, computed, watch } from 'vue'
import type { Resource, ResourceEvent, ResourceSubmission } from '@/models/interfaces/Resource'
import { ResourceService } from '@/services/resources/ResourceService'
import { FormType } from '@/models/enums/app/FormType'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'

export const useResourceStore = defineStore(StoresList.RESOURCES, () => {
  const resources: Ref<Resource[]> = ref([])
  const resource: Ref<Resource | null> = ref(null)
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

  const submitResource = async (resource: ResourceSubmission, type: FormType) => {
    const submittedResource =
      type === FormType.CREATE
        ? await ResourceService.post(resource)
        : await ResourceService.patch(resource)
    if (type === FormType.CREATE) {
      resources.value.push(submittedResource)
    } else if (type === FormType.EDIT || type === FormType.VALIDATE) {
      resources.value.forEach((resource, key) => {
        if (resource.id === submittedResource.id) {
          resources.value[key] = submittedResource
        }
      })
    }
    addNotification(i18n.t(`notifications.resource.${type}`), NotificationType.SUCCESS)
    return submittedResource
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
    isResourceFormShown,
    editedResourceId,
    editedResource,
    nearestEvents,
    getAll,
    submitResource,
    deleteResource,
    getNearestEvents
  }
})
