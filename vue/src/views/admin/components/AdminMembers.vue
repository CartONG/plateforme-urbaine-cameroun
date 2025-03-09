<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Members"
      :items="adminStore.appMembers"
      :sortingListItems="[
        { sortingKey: 'isValidated', text: 'Utilisateurs à valider' },
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
        <template v-if="!(item as User).isValidated">
          <v-btn
            size="small"
            icon="mdi-arrow-right"
            class="text-main-blue"
            @click="editUser(item as User)"
          ></v-btn>
        </template>
        <template v-else>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_ACTORS)"
            icon="mdi-contacts"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_PROJECTS)"
            icon="mdi-rocket-launch"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_DATA)"
            icon="mdi-database-arrow-down"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-icon
            :color="getRoleIconColor(item as User, UserRoles.EDITOR_RESSOURCES)"
            icon="mdi-account-group"
            class="mr-1"
            size="small"
          ></v-icon>
          <v-btn
            density="comfortable"
            icon="mdi-pencil-outline"
            @click="editUser(item as User)"
            :disabled="(item as User).roles.includes(UserRoles.ADMIN)"
            class="disabled-white"
          ></v-btn>
        </template>
      </template>
    </AdminTable>
  </div>
</template>
<script setup lang="ts">
import { useAdminStore } from '@/stores/adminStore'
import { computed, onBeforeMount, ref } from 'vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import AdminTable from '@/components/admin/AdminTable.vue'
import type { User } from '@/models/interfaces/auth/User'
import { UserRoles } from '@/models/enums/auth/UserRoles'
const adminStore = useAdminStore()
onBeforeMount(() => {
  adminStore.getMembers()
})

function createUser() {
  adminStore.setUserEditionMode(null)
}

function editUser(user: User) {
  adminStore.setUserEditionMode(user)
}

const sortingUsersSelectedMethod = ref('isValidated')
const sortedUsers = computed(() => {
  if (sortingUsersSelectedMethod.value === 'isValidated') {
    return adminStore.appMembers.slice().sort((a: User, b: User) => {
      if (a.isValidated !== b.isValidated) {
        return Number(a.isValidated) - Number(b.isValidated)
      }
      return a.lastName.localeCompare(b.lastName)
    })
  }
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
