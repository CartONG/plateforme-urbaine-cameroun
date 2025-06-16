import { AdministrationPanels } from '@/models/enums/app/AdministrationPanels'
import { NotificationType } from '@/models/enums/app/NotificationType'
import { StoresList } from '@/models/enums/app/StoresList'
import type { User } from '@/models/interfaces/auth/User'
import { i18n } from '@/plugins/i18n'
import { UsersService } from '@/services/application/UsersService'
import { addNotification } from '@/services/notifications/NotificationService'
import { UserService } from '@/services/userAndAuth/UserService'
import { defineStore } from 'pinia'
import { reactive, ref, watch, type Reactive, type Ref } from 'vue'
import { useApplicationStore } from './applicationStore'

export const useAdminStore = defineStore(StoresList.ADMIN, () => {
  // AdminPanel is used for navigation in extension panels component
  // AdminItem is indicating which item to display in the edition component as a panel can contain multiple items
  const selectedAdminPanel: Ref<AdministrationPanels> = ref(AdministrationPanels.MEMBERS)
  const selectedAdminItem: Ref<AdministrationPanels | null> = ref(
    AdministrationPanels.CONTENT_ACTORS
  )

  const appMembers: Ref<User[]> = ref([])
  const getMembers = async () => {
    appMembers.value = await UsersService.getMembers(false)
  }

  async function createUser(user: Partial<User>) {
    await UserService.createUser(user)
    await getMembers()
    userEdition.active = false
  }

  const userEdition: Reactive<{ active: boolean; user: User | null }> = reactive({
    active: false,
    user: null
  })
  watch(
    () => userEdition.active,
    () => {
      useApplicationStore().showEditContentDialog = userEdition.active
    }
  )
  function setUserEditionMode(user: User | null) {
    userEdition.user = user
    userEdition.active = true
    useApplicationStore().showEditContentDialog = true
  }

  async function editUser(values: Partial<User>) {
    await UserService.patchUser(values, userEdition.user!.id)
    await getMembers()
    userEdition.active = false
  }

  async function deleteUser(user: Partial<User>) {
    useApplicationStore().isLoading = true
    try {
      await UserService.deleteUser(user).then(() => {
        appMembers.value.forEach((member, key) => {
          if (member.id === user.id) {
            appMembers.value.splice(key, 1)
            addNotification(i18n.t('notifications.user.deleteSuccess'), NotificationType.SUCCESS)
          }
        })
      })
    } catch (error) {
      addNotification(
        i18n.t('notifications.user.deleteError'),
        NotificationType.ERROR,
        error as string
      )
    }
    useApplicationStore().isLoading = false
  }

  return {
    selectedAdminPanel,
    selectedAdminItem,
    appMembers,
    getMembers,
    createUser,
    deleteUser,
    userEdition,
    setUserEditionMode,
    editUser
  }
})
