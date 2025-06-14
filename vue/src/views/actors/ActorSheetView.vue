<template>
  <div class="ActorSheetView SheetView" v-if="actor">
    <div class="SheetView__block SheetView__block--left">
      <div class="SheetView__logoCtn show-sm">
        <img
          loading="lazy"
          :src="actor.logo.contentUrl"
          class="SheetView__logo"
          v-if="actor.logo"
        />
      </div>
      <SheetContentBanner
        :page="CommentOrigin.ACTOR"
        :id="actor.id"
        :slug="actor.slug"
        :title="actor.name"
        :subtitle="actor.acronym"
        :phone="actor.phone"
        :email="actor.email"
        :website="actor.website"
        :isEditable="isEditable"
        :updatedAt="actor.updatedAt"
        :map-route="actorMapRoute"
        :map-btn-tooltip="$t('actorPage.seeLocation')"
        :createdBy="actor.createdBy"
        @edit="editActor"
      />
      <div class="SheetView__contentCtn my-6" v-if="actor.description">
        <div class="SheetView__title SheetView__title--divider">
          {{ $t('actorPage.description') }}
        </div>
        <span v-html="formattedDescription"></span>
      </div>
      <ActorRelatedContent :actor="actor" v-if="!appStore.mobile" />
    </div>
    <div class="SheetView__block SheetView__block--right">
      <div class="SheetView__updatedAtCtn hide-sm">
        <UpdateInfoLabel :user="actor.createdBy" :date="actor.updatedAt" class="justify-end" />
        <PrintButton />
      </div>
      <div class="SheetView__logoCtn hide-sm">
        <img
          loading="lazy"
          :src="actor.logo?.contentsFilteredUrl?.thumbnail"
          alt=""
          v-if="actor.logo?.contentsFilteredUrl?.thumbnail"
          class="SheetView__logo"
        />
      </div>
      <ChipList :items="actor.thematics" />

      <div>
        <div class="SheetView__title SheetView__title--divider">
          <span>{{ $t('forms.odds.title') }}</span>
        </div>
        <div>
          <img
            class="SheetView__ODD mr-2"
            :src="`/img/odd/F-WEB-Goal-${odd}.webp`"
            :alt="odd"
            v-for="odd in actor.odds?.sort((a, b) => parseInt(a) - parseInt(b))"
            :key="odd"
          />
        </div>
      </div>

      <div>
        <div class="SheetView__title SheetView__title--divider mt-lg-12">
          <span>{{ $t('actorPage.adminScope') }}</span>
        </div>
        <span>{{ actor.administrativeScopes.map((x) => $t('actors.scope.' + x)).join(', ') }}</span>
        <AdminBoundariesButton :entity="actor" />
      </div>

      <div>
        <div
          class="SheetView__title SheetView__title--divider"
          v-if="actor.banoc || actor.banocUrl"
        >
          <span>{{ $t('forms.banoc.title') }}</span>
        </div>
        <div class="d-flex flex-column">
          <p v-if="actor.banoc">
            <span class="font-weight-bold">{{ $t('forms.banoc.code') + ' :' }}</span>
            {{ actor.banoc }}
          </p>
          <p v-if="actor.banocUrl">
            <span class="font-weight-bold">{{ $t('forms.banoc.url') + ' :' }}</span>
            <a :href="normalizeUrl(actor.banocUrl)" target="_blank">{{ actor.banocUrl }}</a>
          </p>
        </div>
      </div>

      <div class="SheetView__infoCard" v-if="actor.contactName || actor.contactPosition">
        <div>
          <h5 class="SheetView__title">{{ $t('actorPage.contact') }}</h5>
          <ContactCard :name="actor.contactName" :description="actor.contactPosition" />
        </div>
      </div>

      <div class="SheetView__infoCard" v-if="actor.officeName || actor.officeAddress">
        <div class="d-flex flex-row">
          <v-icon icon="$mapMarkerOutline" color="main-black" />
          <div class="ml-1">
            <p class="font-weight-bold">{{ actor.officeName }}</p>
            <p>{{ actor.officeAddress }}</p>
          </div>
        </div>
      </div>
    </div>
    <ActorRelatedContent :actor="actor" v-if="appStore.mobile" />
    <div class="SheetView__block SheetView__block--bottom">
      <SectionBanner :text="$t('actorPage.images')" v-if="actorImages.length" />
      <ImagesMosaic :images="actorImages" :key="mosaicKey" />
      <ContentDivider />
    </div>
  </div>
</template>
<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue'
import ChipList from '@/components/content/ChipList.vue'
import ContactCard from '@/components/content/ContactCard.vue'
import ContentDivider from '@/components/content/ContentDivider.vue'
import ImagesMosaic from '@/components/content/ImagesMosaic.vue'
import AdminBoundariesButton from '@/components/content/adminBoundaries/AdminBoundariesButton.vue'
import PrintButton from '@/components/global/PrintButton.vue'
import type { Actor } from '@/models/interfaces/Actor'
import { CommentOrigin } from '@/models/interfaces/Comment'
import { formatHTMLForSheetView, normalizeUrl } from '@/services/utils/UtilsService'
import { useActorsStore } from '@/stores/actorsStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useUserStore } from '@/stores/userStore'
import SheetContentBanner from '@/views/_layout/sheet/SheetContentBanner.vue'
import UpdateInfoLabel from '@/views/_layout/sheet/UpdateInfoLabel.vue'
import ActorRelatedContent from '@/views/actors/components/ActorRelatedContent.vue'
import { computed, onMounted, ref, watchEffect } from 'vue'
import { useRoute } from 'vue-router'

const appStore = useApplicationStore()
const userStore = useUserStore()
const actorsStore = useActorsStore()
const actor = computed(() => actorsStore.selectedActor)
const actorMapRoute = computed(() => {
  return actor.value
    ? {
        name: 'map',
        query: { item: actor.value.id }
      }
    : null
})
const actorImages = computed(() => {
  const images = actor.value?.images ?? []
  const externalImages = actor.value?.externalImages ?? []
  return [...images, ...externalImages]
})

const mosaicKey = ref(0)

watchEffect(() => {
  if (actorImages.value) {
    mosaicKey.value++
  }
})

onMounted(async () => {
  appStore.isLoading = true
  const route = useRoute()
  await actorsStore.getActors()
  watchEffect(() => {
    if (actorsStore.dataLoaded) {
      if (actorsStore.selectedActor === null) {
        const actor: Actor | undefined = actorsStore.actors.find(
          (actor) => actor.slug === route.params.slug
        )
        actorsStore.setSelectedActor(actor?.id as string)
        appStore.isLoading = false
      } else {
        appStore.isLoading = false
      }
    }
  })
})

const isEditable = computed(() => {
  return (
    (userStore.userIsAdmin() || actor.value?.createdBy?.id === userStore.currentUser?.id) &&
    userStore.currentUser != null
  )
})

function editActor() {
  actorsStore.setActorEditionMode(actor.value)
}

const formattedDescription = computed(() =>
  formatHTMLForSheetView(actor.value?.description as string)
)
</script>
<style lang="scss">
@import '@/assets/styles/views/SheetView';

.ActorSheetView__projectCardCtn {
  display: flex;
  flex-flow: row wrap;
  justify-content: center;
  gap: 2rem;
  > * {
    flex: 1 0 25rem;
  }
}

.ActorSheetView {
  &__logo {
    max-width: 100%;
  }
  &__contentCard {
    display: flex;
    padding: 1.5em;
    width: 100%;
    background-color: rgb(var(--v-theme-light-yellow));
  }
}

@media (max-width: $bp-xl) {
  .ActorSheetView {
    .SheetView__block--bottom {
      display: none;
    }
  }
}
</style>
