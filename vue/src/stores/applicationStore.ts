import { ContentPagesList } from '@/models/enums/app/ContentPagesList'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { ItemType } from '@/models/enums/app/ItemType'
import { StoresList } from '@/models/enums/app/StoresList'
import type { LikesList } from '@/models/interfaces/LikesList'
import type { Notification } from '@/models/interfaces/Notification'
import { BreadcrumbsService } from '@/services/application/BreadcrumbsService'
import { NavigationTabsService } from '@/services/application/NavigationTabsService'
import { LikeService } from '@/services/likeSystem/LikeService'
import { useProjectStore } from '@/stores/projectStore'
import { defineStore } from 'pinia'
import type { Ref } from 'vue'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useDisplay } from 'vuetify'

export const useApplicationStore = defineStore(StoresList.APPLICATION, () => {
  const { mobile } = useDisplay()
  const isLoading = ref(false)
  const activeTab = ref(0)
  const activeDialog: Ref<DialogKey | null> = ref(null)
  const showEditContentDialog = ref(false)
  const showEditMessageDialog = ref<false | ItemType>(false)
  const showEditThanksDialog = ref(false)
  const route = useRoute()
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

  const isFullViewport = computed(() => {
    const fullViewportRoutes: string[] = ['map']
    return route.name && typeof route.name === 'string' && fullViewportRoutes.includes(route.name)
  })

  const isLightHeader = computed(() => {
    const lightUiRoutes: string[] = ['projects', 'map']
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
    isLoading,
    notificationPile,
    activeTab,
    activeDialog,
    breadcrumbs,
    is100vh,
    isFullViewport,
    isLightHeader,
    triggerZoomReset,
    showEditContentDialog,
    showEditMessageDialog,
    showEditThanksDialog,
    currentContentPage,
    likesList,
    getLikesList,
    addLike,
    deleteLike
  }
})
