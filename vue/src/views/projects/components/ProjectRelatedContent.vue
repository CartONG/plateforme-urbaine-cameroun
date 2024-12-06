<template>
  <div class="SheetView__contentCtn">
    <SectionBanner :text="$t('projectPage.keyNumbers')" />
    <div class="ProjectSheetView__kpiCtn">
      <Kpi v-for="kpi in kpis" :kpi="kpi" :key="kpi.key" :showDescription="true" />
    </div>
  </div>
  <div class="SheetView__contentCtn" v-if="project.calendar">
    <SectionBanner :text="$t('projectPage.projectCalendar')" />
    <p>{{ project.calendar }}</p>
  </div>
  <div class="SheetView__contentCtn" v-if="project.deliverables">
    <SectionBanner :text="$t('projectPage.projectDeliverables')" />
    <p>{{ project.deliverables }}</p>
  </div>
</template>

<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue'
import Kpi from '@/components/content/Kpi.vue'
import { KpiKey } from '@/models/enums/app/KpiKey'
import type { Project } from '@/models/interfaces/Project'
defineProps<{
  project: Project
}>()

const kpis = [
  {
    key: KpiKey.ACTOR,
    count: 0
  },
  {
    key: KpiKey.DATA,
    count: 0
  },
  {
    key: KpiKey.RESOURCE,
    count: 0
  }
]
</script>

<style lang="scss">
.ProjectSheetView__kpiCtn {
  display: flex;
  flex-flow: row wrap;
  gap: 2rem;
  margin: 1.5rem 0;
  justify-content: flex-start;
  align-items: center;

  > * {
    flex: 1 0 10rem;
  }
}
</style>
