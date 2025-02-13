<template>
  <div class="MapPluginsMenu">
    <div class="MapPluginsMenu__title">
      <v-btn
        size="small"
        icon="mdi-arrow-left"
        class="text-dark-grey"
        elevation="4"
        @click="pluginStore.isPluginsMenuVisible = false"
      ></v-btn>
      <span class="ml-2"> {{ $t('pluginsMenu.title') }}</span>
    </div>
    <MyMapPluginsButton :title="$t('pluginsMenu.searchCoords')" />
    <MyMapPluginsButton :title="$t('pluginsMenu.compareBaseMaps')" />
    <MyMapPluginsButton :title="$t('pluginsMenu.calculateItinerary')" />
    <MyMapPluginsButton :title="$t('pluginsMenu.measureDistance')" />
    <MyMapPluginsButton :title="$t('pluginsMenu.draw')" />
    <MyMapPluginsButton :title="$t('pluginsMenu.spatialSelection')" />
  </div>
</template>
<script setup lang="ts">
import { useMyMapPluginsStore } from '@/stores/myMapPluginsStore'
import MyMapPluginsButton from './MyMapPluginsButton.vue'
const pluginStore = useMyMapPluginsStore()
</script>
<style>
.MapPluginsMenu {
  display: flex;
  flex-flow: column nowrap;
  height: 100%;
  width: 100%;
  gap: 0.75rem;
}
.MapPluginsMenu__title {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
  color: rgb(var(--v-theme-main-blue));
  font-weight: 600;
}
</style>
