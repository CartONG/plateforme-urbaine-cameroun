<template>
  <div class="SpaceSheetView" v-if="space">
    <div class="SpaceSheetView__ctn SpaceSheetView__ctn--banner">
      <PageTitle :title="space.name" />
      <div class="SpaceSheetView__description" v-html="formattedDescription"></div>
      <div class="SpaceSheetView__actions">
        <v-btn color="main-blue" variant="outlined" @click="checkAvailability">
          {{ $t('divercity.space.checkAvailability') }}
        </v-btn>
        <v-btn color="main-red" @click="bookSpace">
          {{ $t('divercity.space.bookSpace') }}
        </v-btn>
      </div>
    </div>

    <div class="SpaceSheetView__ctn" v-if="space.photos.length">
      <v-carousel
        cycle
        hide-delimiter-background
        show-arrows="hover"
        height="500"
        class="SpaceSheetView__carousel"
      >
        <v-carousel-item
          v-for="photo in space.photos"
          :key="photo['@id']"
          :src="photo.contentUrl"
          cover
        />
      </v-carousel>
    </div>

    <div class="SpaceSheetView__ctn">
      <DiverCityHighlights />
    </div>

    <div class="SpaceSheetView__ctn" v-if="upcomingBookings.length">
      <SectionBanner :text="$t('divercity.space.upcoming')" />
      <div class="SpaceSheetView__activityGrid SpaceSheetView__activityGrid--upcoming">
        <BookingActivityCard v-for="booking in upcomingBookings" :key="booking.id" :booking="booking" />
      </div>
    </div>

    <div class="SpaceSheetView__ctn" v-if="currentHighlight">
        <!-- <SectionBanner :text="$t('divercity.space.statistics', { year: currentHighlight.year })" /> -->
         <SectionBanner :text="$t('divercity.space.activityReports')" />
        <div class="SpaceSheetView__kpisGrid">
        <!-- <DiverCityStaticKpi
        v-for="stat in currentHighlight.statistics"
        :key="stat.id"
        :label="stat.label"
        :value="stat.value"
        /> -->
    </div>

    <v-btn
        v-if="currentHighlight.report"
        variant="tonal"
        color="main-blue"
        :href="currentHighlight.report.contentUrl"
        target="_blank"
        class="mt-6"
    >
        {{ $t('divercity.space.downloadReport', { year: currentHighlight.year }) }}
    </v-btn>
    </div>
  </div>
</template>

<script setup lang="ts">
import PageTitle from '@/components/text-elements/PageTitle.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import BookingActivityCard from '@/views/divercity/components/BookingActivityCard.vue'
import { formatHTMLForSheetView } from '@/services/utils/UtilsService'
import { useSpacesStore } from '@/stores/divercity/spacesStore'
import { computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useApplicationStore } from '@/stores/applicationStore'
import DiverCityStaticKpi from '@/components/content/DiverCityStaticKpi.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { useUserStore } from '@/stores/userStore'
import DiverCityHighlights from './components/DiverCityHighlights.vue'


const applicationStore = useApplicationStore()
const spacesStore = useSpacesStore()
const router = useRouter()
const route = useRoute()
const userStore = useUserStore()

const space = computed(() => spacesStore.mainSpace)

onMounted(() => {
  applicationStore.isLoading = false
})

const currentHighlight = computed(() => {
  if (!space.value?.highlights?.length) return null
  return [...space.value.highlights].sort((a, b) => b.year - a.year)[0]
})

const formattedDescription = computed(() => formatHTMLForSheetView(space.value?.description as string))
const featuredBookings = computed(() => spacesStore.publicBookings.slice(0, 3))
const upcomingBookings = computed(() => spacesStore.publicBookings.slice(0, 3))


function checkAvailability() {
  goToOrAskLogin('divercitySpaceAvailability')
}

function bookSpace() {
  goToOrAskLogin('divercitySpaceBooking')
}

function goToOrAskLogin(routeName: string) {
  if (!userStore.userIsLogged) {
    router.replace({
      query: { ...route.query, dialog: DialogKey.AUTH_SIGN_IN, redirect: routeName }
    })
    return
  }
  router.push({ name: routeName })
}
</script>

<style lang="scss">
@import '@/assets/styles/views/SheetView';

.SpaceSheetView {
  .SpaceSheetView__ctn {
    display: flex;
    flex-flow: column nowrap;
    max-width: $dim-container-w;
    margin: 4rem auto;
    gap: 1rem;

    &--banner {
      max-width: none;
      margin: 0 auto 4rem;
      padding: 2rem;
      border: 1px solid rgb(var(--v-theme-main-grey));

      > * {
        max-width: $dim-container-w;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
      }
    }
  }

  &__actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
  }

  &__activityGrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;

    &--featured {
      grid-template-columns: repeat(3, 1fr);
    }

    &--upcoming {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  &__kpisGrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin: 1.5rem 0;
    justify-content: flex-start;
    align-items: center;
  }
}

@media (max-width: $bp-xl) {
  .SpaceSheetView {
    .SpaceSheetView__ctn {
      margin: 2rem auto;

      &--banner {
        padding: 1rem;
        margin-bottom: 2rem;
      }
    }

    &__actions {
      flex-flow: column nowrap;

      .v-btn {
        width: 100%;
      }
    }

    &__carousel {
      height: 240px !important;
    }

    &__activityGrid {
      grid-template-columns: repeat(2, 1fr);
    }

    &__kpisGrid {
      grid-template-columns: 1fr;
    }
  }
}
</style>