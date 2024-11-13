import { StoresList } from '@/models/enums/app/StoresList'
import { NavigationTabsService } from '@/services/application/NavigationTabsService'
import { BreadcrumbsService } from '@/services/application/BreadcrumbsService'
import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useDisplay } from 'vuetify'
import { useProjectStore } from '@/stores/projectStore'
import { DialogKey } from '@/models/enums/app/DialogKey'
import type { Ref } from 'vue'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import { useActorsStore } from './actorsStore'

export const useApplicationStore = defineStore(StoresList.APPLICATION, () => {
  const { mobile } = useDisplay()
  const activeTab = ref(0)
  const activeDialog: Ref<DialogKey|null> = ref(null)
  const showEditContentDialog = ref(false)
  const route = useRoute();
  const triggerZoomReset = ref(false)
  const showSnackBar = ref(false)
  const snackBarMessage = ref('')
  const currentContentPage: Ref<ContentPagesList> = ref(ContentPagesList.HOME)

  const breadcrumbs = computed(() => {
    activeTab.value = NavigationTabsService.getTabsNumberFromRoute(route, activeTab.value)
    return BreadcrumbsService.getBreadcrumbsItems(route)
  })

  const is100vh = computed(() => {
    return useProjectStore().isProjectMapFullWidth
  })

  const isLightHeader = computed(() => {
    const lightUiRoutes: string[] = ['projects']
    return route.name && typeof route.name === 'string' && lightUiRoutes.includes(route.name)
  })

  const refreshAppData = () => {
    const actorsStore = useActorsStore()
    actorsStore.getActors(true)
    const projectStore = useProjectStore()
    projectStore.getAll(true)
  }

  return { mobile, showSnackBar, snackBarMessage, activeTab, activeDialog, breadcrumbs, is100vh, isLightHeader, triggerZoomReset, showEditContentDialog, currentContentPage, refreshAppData }
})
