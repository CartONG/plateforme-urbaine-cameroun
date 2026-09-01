<template>
  <div class="DiverCityHighlights" v-if="highlightStore.orderedMainHighlights.length">
    <SectionBanner :text="$t('divercity.space.featuredResources')" />
    <div class="DiverCityHighlights__grid">
      <GenericInfoCard
        v-for="item in highlightStore.orderedMainHighlights"
        :id="item.resourceId"
        :key="item.id"
        :title="item.name"
        :description="item.description"
        :image="item.image"
        :type="ItemType.RESOURCE"
        highlight-mode="divercity"
        :type-label="$t('itemType.resource')"
        :href="item.link"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import GenericInfoCard from '@/components/global/GenericInfoCard.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import { ItemType } from '@/models/enums/app/ItemType'
import { useDiverCityHighlightStore } from '@/stores/divercity/divercityHighlightStore'
import { onMounted } from 'vue'

const highlightStore = useDiverCityHighlightStore()

onMounted(async () => await highlightStore.getMainHighlights())
</script>

<style lang="scss" scoped>
.DiverCityHighlights {
  &__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
  }

  @media (max-width: $bp-lg) {
    &__grid {
      grid-template-columns: 1fr;
    }
  }
}
</style>