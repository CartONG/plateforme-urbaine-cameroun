<template>
  <div class="maplibregl-ctrl maplibregl-ctrl-group">
    <v-btn
      icon="mdi-wrench-outline"
      @click.stop="pluginsStore.isPluginsMenuVisible = !pluginsStore.isPluginsMenuVisible"
      :class="{
        'text-white': pluginsStore.isPluginsMenuVisible,
        'text-main-blue': !pluginsStore.isPluginsMenuVisible
      }"
      :color="pluginsStore.isPluginsMenuVisible ? 'main-blue' : 'white'"
    ></v-btn>
  </div>
</template>
<script setup lang="ts">
import { useMyMapPluginsStore } from '@/stores/myMapPluginsStore'

const pluginsStore = useMyMapPluginsStore()
</script>
