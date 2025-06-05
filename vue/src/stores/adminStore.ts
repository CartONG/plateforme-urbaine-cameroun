import { AdministrationPanels } from '@/models/enums/app/AdministrationPanels'
import { StoresList } from '@/models/enums/app/StoresList'
import type { User } from '@/models/interfaces/auth/User'
import { UsersService } from '@/services/application/UsersService'
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
    console.log(user)
    // await UserService.deleteUser(user).then(() => {
    //   appMembers.value.forEach((member, key) => {
    //     if (member.id === user.id) {
    //       appMembers.value.splice(key, 1)
    //       addNotification(i18n.t('notifications.user.delete'), NotificationType.SUCCESS)
    //     }
    //   })
    // })
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
