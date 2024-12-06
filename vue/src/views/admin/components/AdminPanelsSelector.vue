<template>
  <div class="Admin__panelSelector_container">
    <v-expansion-panels variant="accordion" v-model="adminStore.selectedAdminPanel" elevation="0">
      <v-expansion-panel
        :readonly="true"
        :value="AdministrationPanels.MEMBERS"
        @click="adminStore.selectedAdminPanel = AdministrationPanels.MEMBERS"
        class="text-main-blue"
        :class="{
          Admin__selectedPanel: adminStore.selectedAdminPanel === AdministrationPanels.MEMBERS
        }"
      >
        <v-expansion-panel-title>
          {{ $t('admin.panelMembers') }}
          <template v-slot:actions>
            <v-icon color="main-blue" icon="mdi-chevron-right"></v-icon>
          </template>
        </v-expansion-panel-title>
      </v-expansion-panel>
      <v-expansion-panel
        :title="$t('admin.panelContent')"
        :value="AdministrationPanels.CONTENT"
        :class="{
          Admin__selectedPanel: adminStore.selectedAdminPanel === AdministrationPanels.CONTENT
        }"
        class="text-main-blue"
      >
        <v-expansion-panel-text>
          <router-link class="Admin__itemSelector" :to="{ name: 'adminActors' }">
            <v-icon icon="mdi mdi-circle-small" size="large"></v-icon>
            {{ $t('admin.panelContentActors') }}
            <div class="Admin__itemToValidateCounter">{{ actorsToValidate }}</div>
          </router-link>
          <router-link class="Admin__itemSelector" :to="{ name: 'adminProjects' }">
            <v-icon icon="mdi mdi-circle-small" size="large"></v-icon>
            {{ $t('admin.panelContentProjects') }}
            <div class="Admin__itemToValidateCounter">{{ projectsToValidate }}</div>
          </router-link>
          <div
            class="Admin__itemSelector"
            :class="{
              Admin__itemSelected:
                adminStore.selectedAdminItem === AdministrationPanels.CONTENT_RESOURCES
            }"
            @click="adminStore.selectedAdminItem = AdministrationPanels.CONTENT_RESOURCES"
          >
            <v-icon icon="mdi mdi-circle-small" size="large"></v-icon>
            {{ $t('admin.panelContentResources') }}
          </div>
        </v-expansion-panel-text>
      </v-expansion-panel>
      <v-expansion-panel
        :readonly="true"
        :value="AdministrationPanels.COMMENTS"
        @click="adminStore.selectedAdminPanel = AdministrationPanels.COMMENTS"
        class="text-main-blue"
        :class="{
          Admin__selectedPanel: adminStore.selectedAdminPanel === AdministrationPanels.COMMENTS
        }"
      >
        <v-expansion-panel-title>
          {{ $t('admin.panelComments') }}
          <template v-slot:actions>
            <v-icon color="main-blue" icon="mdi-chevron-right"></v-icon>
          </template>
        </v-expansion-panel-title>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>
<script setup lang="ts">
import { AdministrationPanels } from '@/models/enums/app/AdministrationPanels'
import { useActorsStore } from '@/stores/actorsStore'
import { useAdminStore } from '@/stores/adminStore'
import { useProjectStore } from '@/stores/projectStore'
import { computed, watch } from 'vue'
import { useRouter } from 'vue-router'
const adminStore = useAdminStore()
const actorsStore = useActorsStore()
const projectStore = useProjectStore()
const router = useRouter()
watch(
  () => adminStore.selectedAdminPanel,
  () => {
    if (adminStore.selectedAdminPanel === AdministrationPanels.MEMBERS) {
      router.push({ name: 'adminMembers' })
      adminStore.selectedAdminItem = null
    } else if (adminStore.selectedAdminPanel === AdministrationPanels.CONTENT) {
      router.push({ name: 'adminActors' })
      adminStore.selectedAdminItem = AdministrationPanels.CONTENT_ACTORS
    } else {
      router.push({ name: 'adminComments' })
      adminStore.selectedAdminItem = null
    }
  }
)
const actorsToValidate = computed(() => actorsStore.actors.filter((x) => !x.isValidated).length)
const projectsToValidate = computed(
  () => projectStore.projects.filter((x) => !x.isValidated).length
)
</script>

<style lang="scss">
.Admin {
  &__panelSelector_container {
    width: 100%;
  }
  &__selectedPanel {
    border-left: 4px solid rgb(var(--v-theme-main-blue));
    font-weight: 700;
  }
  &__itemSelector {
    display: flex;
    align-items: center;
    font-weight: 500;
    cursor: pointer;
    height: 40px;
    text-decoration: none;
    color: rgb(var(--v-theme-main-blue));
    &:hover {
      background-color: rgb(var(--v-theme-light-yellow));
    }
    &.router-link-exact-active {
      font-weight: 700;
      background-color: rgb(var(--v-theme-light-yellow));
    }
  }
  &__itemToValidateCounter {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgb(var(--v-theme-main-red));
    border-radius: 1rem;
    height: 16px;
    min-width: 16px;
    padding: 0.25rem;
    color: white;
    font-size: 10px;
    font-weight: 400;
    margin-left: 8px;
  }
}
</style>
