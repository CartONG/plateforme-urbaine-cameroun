<template>
  <v-btn
    class="ToggleSidebarControl"
    variant="elevated"
    :is-active="isToggled"
    elevation="1"
    density="comfortable"
    :size="'2.5rem'"
    icon="mdi-arrow-left"
    :color="isHiglighted ? 'bright-blue' : 'white'"
    @click="isToggled = !isToggled"
  >
    <v-icon
      icon="mdi-chevron-left"
      class="maplibregl-ctrl-icon"
      :inversed-direction="inversedDirection"
      :size="'1.5rem'"
      :color="isHiglighted ? 'white' : 'main-blue'"
    ></v-icon>
  </v-btn>
</template>

<script setup lang="ts">
import { computed } from 'vue'
const isToggled = defineModel<boolean>({ default: false })
const props = withDefaults(
  defineProps<{
    inversedDirection?: boolean
    isHiglightedWhenOff?: boolean
  }>(),
  {
    inversedDirection: false,
    isHiglightedWhenOff: false
  }
)
const isHiglighted = computed(() => !isToggled.value && props.isHiglightedWhenOff)
</script>

<style lang="scss">
.ToggleSidebarControl {
  pointer-events: all;
  &[is-active='true'] {
    .v-icon {
      rotate: 180deg;
    }
  }
  .v-icon {
    transition: all 0.3s ease-in-out;
    &[inversed-direction='true'] {
      transform: scaleX(-1);
    }
  }
}
</style>
