<template>
  <div class="HomeHighlights">
    <GenericInfoCard
      v-for="item in homeStore.orderedMainHighlights"
      :id="item.itemId"
      :title="item.name"
      :description="item.description"
      :image="item.image"
      :type="item.itemType"
      :type-label="$t('itemType.' + item.itemType)"
      :slug="item.slug"
      :href="item.link"
      :key="item.id"
    />
  </div>
</template>

<script setup lang="ts">
import GenericInfoCard from '@/components/global/GenericInfoCard.vue'
import { useHomeStore } from '@/stores/homeStore'

const homeStore = useHomeStore()
</script>

<style lang="scss">
.HomeHighlights {
  display: flex;
  flex-flow: row-reverse nowrap;
  max-width: 100%;
  gap: 2rem;
  & > * {
    flex: 1 0 20rem;
  }
}
@media (max-width: $bp-lg) {
  .HomeHighlights {
    flex-flow: column-reverse nowrap;
  }
}
</style>
