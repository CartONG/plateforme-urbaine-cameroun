<template>
  <div class="MyMapItemPopup" ref="active-item-card">
    <ProjectCard
      v-if="myMapStore.activeItemType === ItemType.PROJECT"
      class="ProjectCard ProjectCard--light"
      :project="myMapStore.activeItem as Project"
      :map="true"
      :active="true"
    />
    <ResourceCard
      v-else-if="myMapStore.activeItemType === ItemType.RESOURCE"
      :resource="myMapStore.activeItem as Resource"
      class="ResourceCard ResourceCard--light"
    />
    <ActorCard
      v-else-if="myMapStore.activeItemType === ItemType.ACTOR"
      :actor="myMapStore.activeItem as Actor"
      class="ActorCard ActorCard--light"
    />
  </div>
</template>

<script setup lang="ts">
import { useMyMapStore } from '@/stores/myMapStore'
import ProjectCard from '@/views/projects/components/ProjectCard.vue'
import { computed, onMounted, useTemplateRef, watch } from 'vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import { ItemType } from '@/models/enums/app/ItemType'
import type { Project } from '@/models/interfaces/Project'
import type { Resource } from '@/models/interfaces/Resource'
import ResourceCard from '@/views/resources/components/ResourceCard.vue'
import type { Actor } from '@/models/interfaces/Actor'

const myMapStore = useMyMapStore()
const activeItemCard = useTemplateRef('active-item-card')

const myMap = computed(() => myMapStore.myMap)
const map = computed(() => myMap.value?.map)

onMounted(() => {
  if (map.value != null) {
    showPopup()
  }
})

watch(
  () => myMap.value?.activeFeatureId,
  (to, from) => {
    const initializing = from === undefined
    if (myMap.value == null || initializing) return
    myMapStore.activeItemId = myMap.value?.activeFeatureId
  }
)

watch(
  () => myMapStore.activeItem,
  () => {
    if (myMap.value == null) return
    showPopup()
  }
)

const showPopup = () => {
  if (myMap.value == null) return
  Object.values(ItemType).forEach((itemType) => {
    myMap.value?.addPopupOnClick(itemType, activeItemCard.value, false)
  })
}
</script>

<style lang="scss">
.MyMap {
  width: 100%;
  height: 100%;
  position: relative;

  .maplibregl-popup,
  .MyMapItemPopup .v-card {
    max-width: 22rem !important;
  }

  #map.MyMap__map {
    width: 100%;
    height: 100%;

    .maplibregl-ctrl-top-right {
      align-items: flex-end;
    }
  }
}
</style>
