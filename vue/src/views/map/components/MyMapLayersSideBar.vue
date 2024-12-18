<template>
  <div class="MyMapLayersSideBar">
    <MyMapPlatformLayers class="MyMapLayersSideBar__platformLayers" />
  </div>
</template>

<script setup lang="ts">
import MyMapPlatformLayers from '@/views/map/components/MyMapPlatformLayers.vue'
</script>

<style lang="scss">
.MyMapLayersSideBar {
  display: flex;
  flex-flow: column nowrap;
  flex: 1 0 auto;
  width: 19rem;
  background: #fff;
  padding: 1rem;
  gap: 1rem;
}
</style>
