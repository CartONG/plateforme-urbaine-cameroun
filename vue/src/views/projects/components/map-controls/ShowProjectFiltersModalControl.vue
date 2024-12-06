<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group maplibregl-ctrl-show-project-filters-ctn">
    <button
      class="maplibregl-ctrl-show-project-filters"
      @click="projectStore.isFilterModalShown = true"
    >
      <span class="maplibregl-ctrl-icon"></span>
      <span class="maplibregl-ctrl-text">{{ $t('projects.map.filterProjects') }}</span>
    </button>
  </div>
</template>

<script setup lang="ts">
import { useProjectStore } from '@/stores/projectStore'
const projectStore = useProjectStore()
</script>

<style lang="scss">
.maplibregl-ctrl-toggle-sidebar {
  transition: all 0.3s ease-in-out;

  &[active='true'] {
    transform: rotate(180deg);
    box-shadow: 0 -0.25rem 0.25rem -0.125rem rgba(0, 0, 0, 0.15);
  }

  .maplibregl-ctrl-icon {
    background-image: url(@/assets/images/icons/map/mdi-toggle.svg);
  }
}
</style>
