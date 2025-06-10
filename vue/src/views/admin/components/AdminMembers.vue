<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Members"
      :items="adminStore.appMembers"
      :sortingListItems="[
        { sortingKey: 'firstName', text: 'Prénom' },
        { sortingKey: 'lastName', text: 'Nom' },
        { sortingKey: 'email', text: 'Email' }
      ]"
      searchKey="email"
      @updateSortingKey="sortingUsersSelectedMethod = $event"
      @update-search-query="searchQuery = $event"
    >
      <template #right-buttons>
        <v-btn @click="createUser" color="main-red">{{ $t('admin.add') }}</v-btn>
      </template>
    </AdminTopBar>
    <AdminTable
      :items="filteredItems"
      :tableKeys="['lastName', 'firstName', 'email']"
      :columnWidths="['20%', '20%', '30%', '30%']"
      :plainText="true"
    >
      <template #editContentCell="{ item }">
        <template v-if="(item as User).isValidated">
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_ACTORS)"
            icon="$contacts"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_PROJECTS)"
            icon="$rocketLaunch"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_DATA)"
            icon="$databaseArrowDown"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_RESSOURCES)"
            icon="$accountGroup"
            class="mr-1"
            size="small"
          ></v-icon>
        </template>
        <v-btn
          density="comfortable"
          icon="$pencilOutline"
          @click="editUser(item as User)"
          class="disabled-white"
        ></v-btn>
        <v-btn
          density="comfortable"
          icon="$deleteOutline"
          @click="((isAreYouSurePopupShown = true), (userToDelete = item as Partial<User>))"
          class="disabled-white"
        ></v-btn>
        <AreYouSurePopup
          :shown="isAreYouSurePopupShown"
          :loading="isDeleting"
          @hide="((isAreYouSurePopupShown = false), (userToDelete = null))"
          @confirm="deleteUser()"
        />
      </template>
    </AdminTable>
  </div>
</template>
<script setup lang="ts">
import AdminTable from '@/components/admin/AdminTable.vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import AreYouSurePopup from '@/components/global/AreYouSurePopup.vue'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import type { User } from '@/models/interfaces/auth/User'
import { useAdminStore } from '@/stores/adminStore'
import { computed, onBeforeMount, ref, type Ref } from 'vue'
const adminStore = useAdminStore()
const isAreYouSurePopupShown = ref(false)
const isDeleting = ref(false)
onBeforeMount(() => {
  adminStore.getMembers()
})

function createUser() {
  adminStore.setUserEditionMode(null)
}

function editUser(user: User) {
  adminStore.setUserEditionMode(user)
}

const userToDelete: Ref<Partial<User> | null> = ref(null)
const deleteUser = async () => {
  isDeleting.value = true
  await adminStore.deleteUser(userToDelete.value as Partial<User>)
  isDeleting.value = false
  userToDelete.value = null
  isAreYouSurePopupShown.value = false
}

const sortingUsersSelectedMethod = ref('lastName')
const sortedUsers = computed(() => {
  if (sortingUsersSelectedMethod.value === 'firstName') {
    return adminStore.appMembers.slice().sort((a: User, b: User) => {
      return a.firstName.localeCompare(b.firstName)
    })
  }
  if (sortingUsersSelectedMethod.value === 'lastName') {
    return adminStore.appMembers.slice().sort((a: User, b: User) => {
      return a.lastName.localeCompare(b.lastName)
    })
  }
  if (sortingUsersSelectedMethod.value === 'email') {
    return adminStore.appMembers.slice().sort((a: User, b: User) => {
      return a.email.localeCompare(b.email)
    })
  }
  return adminStore.appMembers
})

const searchQuery = ref('')
const filteredItems = computed(() => {
  if (!searchQuery.value) {
    return sortedUsers.value
  }
  return sortedUsers.value.filter((item: User) => {
    return (
      item.firstName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.lastName.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.organisation?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})

const getRoleIconColor = (user: User, role: UserRoles) => {
  if (user.roles.includes(role) || user.roles.includes(UserRoles.ADMIN)) {
    return 'main-green'
  } else if (user.requestedRoles?.includes(role)) {
    return 'main-red'
  }
  return 'main-grey'
}
</script>

<style scoped>
.disabled-white.v-btn--disabled {
  color: white;
}
</style>
