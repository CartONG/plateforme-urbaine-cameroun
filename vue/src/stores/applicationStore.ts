import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { StoresList } from '@/models/enums/app/StoresList'
import { NavigationTabsService } from '@/services/application/NavigationTabsService'
import { BreadcrumbsService } from '@/services/application/BreadcrumbsService'
import { useRoute } from 'vue-router'
import { useDisplay } from 'vuetify'
import { useProjectStore } from '@/stores/projectStore'
import { DialogKey } from '@/models/enums/app/DialogKey'
import type { Ref } from 'vue'
import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import type { LikesList } from '@/models/interfaces/LikesList'
import { LikeService } from '@/services/likeSystem/LikeService'
import type { Notification } from '@/models/interfaces/Notification'

export const useApplicationStore = defineStore(StoresList.APPLICATION, () => {
  const { mobile } = useDisplay()
  const activeTab = ref(0)
  const activeDialog: Ref<DialogKey|null> = ref(null)
  const showEditContentDialog = ref(false)
  const route = useRoute();
  const triggerZoomReset = ref(false)
  const notificationPile: Ref<Notification[]> = ref([])
  const currentContentPage: Ref<ContentPagesList> = ref(ContentPagesList.HOME)
  const likesList: Ref<LikesList | null> = ref(null)

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

  async function getLikesList() {
    likesList.value = await LikeService.getLikesList()
  }

  async function addLike(id: string) {
    await LikeService.addLike(id)
    await getLikesList()
  }

  async function deleteLike(id: number) {
    await LikeService.deleteLike(id)
    await getLikesList()
  }
  
  return { 
    mobile,
    notificationPile,
    activeTab,
    activeDialog,
    breadcrumbs,
    is100vh,
    isLightHeader,
    triggerZoomReset,
    showEditContentDialog,
    currentContentPage,
    likesList,
    getLikesList,
    addLike,
    deleteLike
  }
})
