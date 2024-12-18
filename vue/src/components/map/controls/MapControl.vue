<template>
  <v-btn class="MapControl" variant="elevated" elevation="1" density="comfortable" :size="'2.5rem'">
    <v-icon :icon="icon" class="maplibregl-ctrl-icon" :size="'1.5rem'"></v-icon>
  </v-btn>
</template>

<script setup lang="ts">
defineProps<{
  icon: string
}>()
</script>

<style lang="scss">
.MapControl {
  pointer-events: all;
  box-shadow: 0 0.25rem 0.25rem -0.125rem rgba(0, 0, 0, 0.15);
}
</style>
