<template>
  <router-link
    v-if="!userStore.userIsLogged"
    append
    :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_SIGN_IN } }"
  >
    <v-btn color="main-red" prepend-icon="mdi-account-circle" flat>
      {{ $t('header.login') }}
    </v-btn>
  </router-link>

  <v-menu v-else class="cursor-pointer">
    <template v-slot:activator="{ props }">
      <v-btn
        base-color="white"
        class="text-main-blue"
        prepend-icon="mdi-account-circle"
        v-bind="props"
        flat
      >
        {{ $t('header.account') }}
      </v-btn>
    </template>

    <v-list>
      <v-list-item
        v-if="userStore.userHasRole(UserRoles.EDITOR_PROJECTS) || userStore.userIsAdmin()"
        @click="projectStore.isProjectFormShown = true"
      >
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-pencil-outline"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.addProject') }}</v-list-item-title>
      </v-list-item>
      <v-list-item
        v-if="userStore.userHasRole(UserRoles.EDITOR_ACTORS) || userStore.userIsAdmin()"
        @click="actorsStore.setActorEditionMode(null)"
      >
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-pencil-outline"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.addActor') }}</v-list-item-title>
      </v-list-item>
      <v-list-item
        v-if="userStore.userHasRole(UserRoles.EDITOR_RESSOURCES) || userStore.userIsAdmin()"
        @click="resourceStore.isResourceFormShown = true"
      >
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-pencil-outline"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.addResource') }}</v-list-item-title>
      </v-list-item>
      <v-list-item v-if="userStore.userHasRole(UserRoles.EDITOR_DATA) || userStore.userIsAdmin()">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-pencil-outline"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.addData') }}</v-list-item-title>
      </v-list-item>
      <v-divider v-if="userStore.userIsEditor()" />
      <v-list-item :to="{ name: 'userAccount' }">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-account-circle"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.account') }}</v-list-item-title>
      </v-list-item>
      <v-list-item v-if="userStore.userIsEditor()">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-tune"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.content') }}</v-list-item-title>
      </v-list-item>
      <v-list-item v-if="userStore.userIsAdmin()" :to="{ name: 'admin' }">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-tune"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.administration') }}</v-list-item-title>
      </v-list-item>
      <v-list-item @click="userStore.signOut()">
        <template v-slot:prepend>
          <v-icon color="main-blue" icon="mdi-logout"></v-icon>
        </template>
        <v-list-item-title>{{ $t('header.logout') }}</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
<script setup lang="ts">
import { UserRoles } from '@/models/enums/auth/UserRoles'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { useActorsStore } from '@/stores/actorsStore'
import { useUserStore } from '@/stores/userStore'
import { useProjectStore } from '@/stores/projectStore'
import { useResourceStore } from '@/stores/resourceStore'

const userStore = useUserStore()
const actorsStore = useActorsStore()
const projectStore = useProjectStore()
const resourceStore = useResourceStore()
</script>
