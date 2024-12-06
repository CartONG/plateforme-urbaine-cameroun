<template>
  <div class="HomeKpis">
    <Kpi v-for="(kpi, key) in homeStore.globalKpis" :kpi="kpi" :key="key" />
  </div>
</template>

<script setup lang="ts">
import Kpi from '@/components/content/Kpi.vue'
import { useHomeStore } from '@/stores/homeStore'

const homeStore = useHomeStore()
</script>

<style lang="scss">
.HomeKpis {
  display: grid;
  grid-template-columns: repeat(2, minmax(150px, 2fr));
  gap: 2rem;
  margin: 1.5rem 0;
  justify-content: flex-start;
  align-items: center;
}

@media (max-width: $bp-sm) {
  .HomeKpis {
    grid-template-columns: 1fr;
  }
}
</style>
