<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group">
    <button
      class="maplibregl-ctrl-toggle-sidebar"
      :active="projectStore.isProjectMapFullWidth"
      @click="projectStore.isProjectMapFullWidth = !projectStore.isProjectMapFullWidth"
    >
      <span class="maplibregl-ctrl-icon"></span>
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
