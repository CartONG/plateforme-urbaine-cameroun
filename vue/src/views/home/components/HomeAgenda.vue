<template>
  <SectionBanner
    :text="$t('home.agenda.title')"
    :action-label="$t('home.agenda.action')"
    :action-link="{ name: 'resources', query: { type: 'events' } }"
    v-if="resourceStore.nearestEvents.length"
  />
  <div class="HomeAgenda">
    <ResourceCard v-for="item in resourceStore.nearestEvents" :resource="item" :key="item.id" />
  </div>
</template>

<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue'
import { useResourceStore } from '@/stores/resourceStore'
import ResourceCard from '@/views/resources/components/ResourceCard.vue'
import { onBeforeMount } from 'vue'

const resourceStore = useResourceStore()
onBeforeMount(async () => await resourceStore.getNearestEvents())
</script>

<style lang="scss">
.HomeAgenda {
  display: flex;
  flex-flow: row nowrap;
  max-width: 100%;
  gap: 2rem;
  & > * {
    flex: 1 0 20rem;
  }

  .ResourceCard {
    max-height: 5rem;
  }
}
@media (max-width: $bp-lg) {
  .HomeAgenda {
    flex-flow: column nowrap;
  }
}
</style>
