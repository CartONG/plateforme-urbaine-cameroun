<template>
  <div class="UiView">
    <h2>Design System</h2>
    <div class="UiView__componentCtn">
      <h4>BasicCard</h4>
      <BasicCard icon="$openInNew">
        <a :href="''" target="_blank" class="ml-2">{{ $t('content.website') }}</a>
      </BasicCard>
    </div>
    <div class="UiView__componentCtn">
      <h4>Pagination</h4>
      <Pagination :items="items" v-model="paginatedItems" :items-per-page="5" />
    </div>

    <div class="UiView__componentCtn">
      <h4>InfoCard</h4>
      <InfoCard :to="'ui'">
        <template #content>
          <span class="InfoCard__subTitle">Sub title</span>
          <span class="InfoCard__title">Title</span>
        </template>
        <template #footer-left>
          <ShareButton :additionnal-path="'A_shared_link'" />
        </template>
        <template #footer-right>
          <v-icon class="InfoCard__actionIcon" icon="$arrowRight"></v-icon>
        </template>
      </InfoCard>
      <h4>InfoCard <code>:light="true"</code></h4>
      <InfoCard :to="'ui'" :light="true">
        <template #content>
          <span class="InfoCard__subTitle">Sub title</span>
          <span class="InfoCard__title">Title</span>
        </template>
        <template #footer-left>
          <ShareButton :additionnal-path="'A_shared_link'" />
        </template>
        <template #footer-right>
          <v-icon class="InfoCard__actionIcon" icon="$arrowRight"></v-icon>
        </template>
      </InfoCard>
    </div>

    <div class="UiView__componentCtn">
      <h4>Geocoding</h4>
      <Geocoding :search-type="NominatimSearchType.FREE" />
    </div>

    <div class="UiView__componentCtn">
      <h4>LocationSelector</h4>
      <LocationSelector />
    </div>

    <div class="UiView__componentCtn">
      <h4>DateInput</h4>
      <DateInput v-model:start-at="startAt" v-model:end-at="endAt" />
    </div>

    <div class="UiView__componentCtn">
      <h4>FileInput</h4>
      <FileInput v-model="file" />
    </div>
  </div>
</template>

<script setup lang="ts">
import DateInput from '@/components/forms/DateInput.vue'
import FileInput from '@/components/forms/FileInput.vue'
import Geocoding from '@/components/forms/Geocoding.vue'
import LocationSelector from '@/components/forms/LocationSelector.vue'
import BasicCard from '@/components/global/BasicCard.vue'
import InfoCard from '@/components/global/InfoCard.vue'
import Pagination from '@/components/global/Pagination.vue'
import ShareButton from '@/components/global/ShareButton.vue'
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType'
import { useActorsStore } from '@/stores/actorsStore'
import { computed, ref } from 'vue'
const actorsStore = useActorsStore()

/* Pagination.vue */
const items = computed(() => actorsStore.actors)
const paginatedItems = ref([])
const file = ref()
const startAt = ref()
const endAt = ref()
</script>

<style lang="scss">
.UiView {
  display: flex;
  flex-flow: column nowrap;
  gap: 2rem;
  &__componentCtn {
    display: flex;
    flex-flow: column nowrap;
    gap: 1rem;

    h4 {
      code {
        background-color: #f5f5f5;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
      }
    }
  }
}
</style>
