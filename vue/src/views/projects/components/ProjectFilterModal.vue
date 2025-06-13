<template>
  <Modal :title="$t('projects.popup.filters.title')">
    <template #content>
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
          v-model="projectStore.filters.administrativeScopes"
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
            v-for="thematic in Object.values(Thematic)"
            :key="thematic"
            :value="thematic"
            variant="outlined"
            >{{ $t('forms.thematics.' + thematic) }}</v-chip
          >
        </v-chip-group>
      </div>

      <div class="Modal__block">
        <label class="Modal__label">{{ $t('projects.popup.filters.beneficiaries.label') }}</label>
        <v-chip-group v-model="projectStore.filters.odds" column multiple class="Modal__chipGroup">
          <v-chip v-for="odd in Object.values(ODD)" :key="odd" :value="odd" variant="outlined">{{
            $t(`forms.odds.${odd}`)
          }}</v-chip>
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
import { ODD } from '@/models/enums/contents/ODD'
import { Status } from '@/models/enums/contents/Status'
import { Thematic } from '@/models/enums/contents/Thematic'
import { useProjectStore } from '@/stores/projectStore'

const projectStore = useProjectStore()

const resetFilters = () => {
  projectStore.resetFilters()
}
</script>
