import { AdministrationPanels } from '@/models/enums/AdministrationPanels'
import { StoresList } from '@/models/enums/StoresList'
import { defineStore } from 'pinia'
import { ref, watch, type Ref } from 'vue'


export const useAdminStore = defineStore(StoresList.ADMIN, () => {
    // AdminPanel is used for navigation in extension panels component
    // AdminItem is indicating which item to display in the edition component as a panel can contain multiple items
  const selectedAdminPanel:Ref<AdministrationPanels> = ref(AdministrationPanels.MEMBERS)
  const selectedAdminItem:Ref<AdministrationPanels | null> = ref(AdministrationPanels.CONTENT_ACTORS)
  return { selectedAdminPanel, selectedAdminItem }
})