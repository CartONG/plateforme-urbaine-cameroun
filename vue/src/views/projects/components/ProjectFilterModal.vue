<template>
  <Modal :title="$t('projects.popup.filters.title')">
    <template #content>
      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.projectOwner.label') }}</label>
        <v-autocomplete
          variant="outlined"
          hide-details="auto"
          density="compact"
          :placeholder="$t('placeholders.all')"
          v-model="projectStore.filters.contractingOrganisations"
          :items="contractingOrganisations"
          item-title="name"
          item-value="id"
          multiple
        ></v-autocomplete>
      </div>
      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.financial.label') }}</label>
        <v-autocomplete
          variant="outlined"
          hide-details="auto"
          density="compact"
          :placeholder="$t('placeholders.all')"
          v-model="projectStore.filters.donors"
          :items="donors"
          item-title="name"
          item-value="id"
          multiple
        ></v-autocomplete>
      </div>
      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.beneficiaries.label') }}</label>
        <v-chip-group
          v-model="projectStore.filters.beneficiaryTypes"
          column
          multiple
          class="Modal__chipGroup"
        >
          <v-chip
            v-for="(beneficiaryType, key) in BeneficiaryType"
            :key="key"
            :value="beneficiaryType"
            variant="outlined"
            >{{ $t(`beneficiaryType.${beneficiaryType}`) }}</v-chip
          >
        </v-chip-group>
      </div>
      <div class="Modal__block">
        <label class="Modal__label">{{
          $t('projects.popup.filters.interventionZones.label')
        }}</label>
        <v-chip-group
          v-model="projectStore.filters.interventionZones"
          column
          multiple
          class="Modal__chipGroup"
        >
          <v-chip
            v-for="(scope, key) in AdministrativeScope"
            :key="key"
            :value="scope"
            variant="outlined"
            >{{ $t(`projects.scope.${scope}`) }}</v-chip
          >
        </v-chip-group>
      </div>
      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.thematics.label') }}</label>
        <v-chip-group
          v-model="projectStore.filters.thematics"
          column
          multiple
          class="Modal__chipGroup"
        >
          <v-chip
            v-for="thematic in thematics"
            :key="thematic.id"
            :value="thematic.id"
            variant="outlined"
            >{{ thematic.name }}</v-chip
          >
        </v-chip-group>
      </div>
      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.status.label') }}</label>
        <v-chip-group
          v-model="projectStore.filters.statuses"
          column
          multiple
          class="Modal__chipGroup"
        >
          <v-chip v-for="(status, key) in Status" :key="key" :value="status" variant="outlined">{{
            $t(`projects.status.${status}`)
          }}</v-chip>
        </v-chip-group>
      </div>
    </template>
    <template #footer-left>
      <span class="text-action" @click="resetFilters">{{ $t('labels.reset') }}</span>
    </template>
    <template #footer-right>
      <v-btn color="main-red" @click="$emit('close')">{{
        $t('projects.popup.showTheProjects', { count: projectStore.filteredProjects.length })
      }}</v-btn>
    </template>
  </Modal>
</template>
<script setup lang="ts">
import Modal from '@/components/global/Modal.vue'
import { AdministrativeScope } from '@/models/enums/AdministrativeScope'
import { BeneficiaryType } from '@/models/enums/contents/BeneficiaryType'
import { Status } from '@/models/enums/contents/Status'
import { uniqueArray } from '@/services/utils/UtilsService'
import { useProjectStore } from '@/stores/projectStore'
import { useThematicStore } from '@/stores/thematicStore'
import { computed, onBeforeMount } from 'vue'

const thematicsStore = useThematicStore()
onBeforeMount(async () => await thematicsStore.getAll())

const projectStore = useProjectStore()
const contractingOrganisations = computed(() =>
  uniqueArray(projectStore.projects.map((project) => project.contractingOrganisation))
)
const donors = computed(() =>
  uniqueArray(projectStore.projects.map((project) => project.donors).flat())
)
const thematics = computed(() => thematicsStore.thematics)

const resetFilters = () => {
  projectStore.resetFilters()
}
</script>
