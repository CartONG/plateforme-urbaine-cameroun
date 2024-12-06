<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group">
    <button
      class="maplibregl-ctrl-zoom-extent"
      @click="applicationStore.triggerZoomReset = !applicationStore.triggerZoomReset"
    >
      <span class="maplibregl-ctrl-icon"></span>
    </button>
  </div>
</template>

<script setup lang="ts">
import { useApplicationStore } from '@/stores/applicationStore'
const applicationStore = useApplicationStore()
</script>

<style lang="scss">
.maplibregl-ctrl-zoom-extent {
  .maplibregl-ctrl-icon {
    background-image: url(@/assets/images/icons/map/mdi-image-filter-center-focus-strong-outline.svg);
  }
}
</style>
