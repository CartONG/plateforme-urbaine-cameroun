<template>
  <GenericInfoCard
    v-if="resource"
    :id="resource.id"
    :href="ResourceService.getLink(resource)"
    :title="resource.name"
    :description="resource.description"
    :type="ItemType.RESOURCE"
    :type-label="$t('resources.resourceType.' + resource.type)"
    :action-icon="icon"
    :is-editable="isEditable"
    class="ResourceCard"
    :edit-function="editResource"
    :slug="resource.slug"
  >
    <template #image v-if="isEvent && resource.startAt">
      <div class="ResourceCard__dateBanner">
        <span class="ResourceCard__date">{{ date }}</span>
        <span class="ResourceCard__month">{{ month }}</span>
      </div>
    </template>
    <template #description>
      <span class="InfoCard__title">{{ resource.name }}</span>
      <span
        class="InfoCard__subTitle"
        v-if="(isEvent && resource.startAt) || resource.geoData?.name"
      >
        <span v-if="isEvent && resource.startAt">
          <v-icon icon="mdi-calendar" />
          <span>{{ dateRangeLabel }}</span>
        </span>
        <span class="InfoCard__location" v-if="resource.geoData?.name">
          <v-icon icon="mdi-map-marker-outline" />
          <span>{{ locationName }}</span>
        </span>
      </span>
      <span class="InfoCard__description">{{ resource.description }}</span>
    </template>
    <template #footer-right>
      <v-icon class="InfoCard__actionIcon" :icon="icon" color="light-blue"></v-icon>
    </template>
  </GenericInfoCard>
</template>

<script setup lang="ts">
import type { Resource } from '@/models/interfaces/Resource'
import { ItemType } from '@/models/enums/app/ItemType'
import GenericInfoCard from '@/components/global/GenericInfoCard.vue'
import { ResourceFormat } from '@/models/enums/contents/ResourceFormat'
import { computed } from 'vue'
import { useResourceStore } from '@/stores/resourceStore'
import { ResourceType } from '@/models/enums/contents/ResourceType'
import { getDateRangeLabel, localizeDate } from '@/services/utils/UtilsService'
import GeocodingService from '@/services/map/GeocodingService'
import { ResourceService } from '@/services/resources/ResourceService'
import { useUserStore } from '@/stores/userStore'

const resourceStore = useResourceStore()
const userStore = useUserStore()
const props = defineProps<{
  resource: Resource
}>()

const icon = computed(() => {
  switch (props.resource.format) {
    case ResourceFormat.VIDEO:
    case ResourceFormat.WEB:
      return 'mdi-open-in-new'
    case ResourceFormat.XLSX:
    case ResourceFormat.PDF:
      return 'mdi-download'
    case ResourceFormat.IMAGE:
      return 'mdi-arrow-right'
    default:
      return undefined
  }
})

const isEditable = computed(() => {
  return (
    userStore.userIsAdmin() ||
    (props.resource?.createdBy?.id === userStore.currentUser?.id &&
      userStore.currentUser?.id != null)
  )
})
const locationName = computed(() => GeocodingService.getLocationName(props.resource.geoData))
const dateRangeLabel = computed(() =>
  getDateRangeLabel(props.resource.startAt, props.resource.endAt)
)
const isEvent = computed(() => props.resource.type === ResourceType.EVENTS)
const date = computed(() => new Date(props.resource.startAt).getDate())
const month = computed(() => localizeDate(props.resource.startAt, { month: 'short' }))

const editResource = () => {
  resourceStore.isResourceFormShown = true
  resourceStore.editedResourceId = props.resource.id
}
</script>

<style lang="scss">
.ResourceCard {
  .InfoCard__subTitle {
    font-weight: 500 !important;
  }
  .ResourceCard__dateBanner {
    background: rgb(var(--v-theme-main-yellow));
    color: rgb(var(--v-theme-main-blue));
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    padding: 1rem;
    position: absolute;
    min-width: 5.25rem;
    top: 0;
    right: 1rem;
    border-radius: 0 0 $dim-radius $dim-radius;

    .ResourceCard__date {
      font-weight: 700;
      font-size: 2.25rem;
      line-height: 2.25rem;
    }
    .ResourceCard__month {
      font-size: 1.25rem;
      text-transform: uppercase;
    }
  }
}
</style>
