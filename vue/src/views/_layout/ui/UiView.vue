<template>
  <div class="UiView">
    <h2>Design System</h2>
    <div class="UiView__componentCtn">
      <h4>BasicCard</h4>
      <BasicCard icon="mdi-open-in-new" >
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
          <span class="InfoCard__subTitle" >Sub title</span>
          <span class="InfoCard__title">Title</span>
        </template>
        <template #footer-left>
            <ShareButton :additionnal-path="'dededed'"/>
        </template>
        <template #footer-right>
            <v-icon class="InfoCard__actionIcon" icon="mdi mdi-arrow-right" color="light-blue"></v-icon>
        </template>
      </InfoCard>
      <h4>InfoCard <code>:light="true"</code></h4>
      <InfoCard :to="'ui'" :light="true" >
        <template #content>
          <span class="InfoCard__subTitle" >Sub title</span>
          <span class="InfoCard__title">Title</span>
        </template>
        <template #footer-left>
            <ShareButton :additionnal-path="'dededed'"/>
        </template>
        <template #footer-right>
            <v-icon class="InfoCard__actionIcon" icon="mdi mdi-arrow-right" color="light-blue"></v-icon>
        </template>
      </InfoCard>
    </div>
    
    <div class="UiView__componentCtn">
      <h4>Geocoding</h4>
      <Geocoding :search-type="NominatimSearchType.FREE" />
    </div>
  </div>
</template>

<script setup lang="ts">
import Geocoding from '@/components/forms/Geocoding.vue';
import BasicCard from '@/components/global/BasicCard.vue';
import InfoCard from '@/components/global/InfoCard.vue';
import LikeButton from '@/components/global/LikeButton.vue';
import ShareButton from '@/components/global/ShareButton.vue';
import { NominatimSearchType } from '@/models/enums/geo/NominatimSearchType';
import Pagination from '@/components/global/Pagination.vue';
import { useActorsStore } from '@/stores/actorsStore';
import { computed, ref } from 'vue';
const actorsStore = useActorsStore()

/* Pagination.vue */
const items = computed(() => actorsStore.actors)
const paginatedItems = ref([])

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