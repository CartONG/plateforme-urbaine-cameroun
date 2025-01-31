import { StoresList } from '@/models/enums/app/StoresList'
import { defineStore } from 'pinia'
import { ref, type Ref } from 'vue'
import type { QgisMap } from '@/models/interfaces/QgisMap'
import { QgisMapService } from '@/services/qgisMap/QgisMapService'
import { FormType } from '@/models/enums/app/FormType'
import { i18n } from '@/plugins/i18n'
import { addNotification } from '@/services/notifications/NotificationService'
import { NotificationType } from '@/models/enums/app/NotificationType'

export const useQgisMapStore = defineStore(StoresList.QGIS_MAP, () => {
  const qgisMaps: Ref<QgisMap[]> = ref([])
  const isQgisMapFormShown = ref(false)
  const isQgisMapVisualiserShown = ref(false)

  async function getAll(): Promise<void> {
    if (qgisMaps.value.length === 0) {
      qgisMaps.value = await QgisMapService.getAll()
    }
  }

  const submitQgisMap = async (qgisMap: QgisMap, type: FormType) => {
    const submittedQgisMap =
      type === FormType.CREATE
        ? await QgisMapService.post(qgisMap)
        : await QgisMapService.patch(qgisMap)
    if (type === FormType.CREATE) {
      qgisMaps.value.push(submittedQgisMap)
    } else if (type === FormType.EDIT || type === FormType.VALIDATE) {
      qgisMaps.value.forEach((qgisMap, key) => {
        if (qgisMap.id === submittedQgisMap.id) {
          qgisMaps.value[key] = submittedQgisMap
        }
      })
    }
    addNotification(i18n.t(`notifications.qgismap.${type}`), NotificationType.SUCCESS)
    return submittedQgisMap
  }

  const deleteQgisMap = async (qgisMap: QgisMap) => {
    await QgisMapService.delete(qgisMap)
    qgisMaps.value.forEach((p, key) => {
      if (p.id === qgisMap.id) {
        qgisMaps.value.splice(key, 1)
        addNotification(i18n.t('notifications.qgismap.delete'), NotificationType.SUCCESS)
      }
    })
  }

  return {
    qgisMaps,
    isQgisMapVisualiserShown,
    isQgisMapFormShown,
    getAll,
    submitQgisMap,
    deleteQgisMap
  }
})
