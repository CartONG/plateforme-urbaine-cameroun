<template>
  <div class="ActorSheetView SheetView" v-if="actor">
    <div class="SheetView__block SheetView__block--left">
      <div class="SheetView__logoCtn show-sm">
        <img :src="actor.logo.contentUrl" class="SheetView__logo" v-if="actor.logo" />
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
      >
        <template #mapButton></template>
      </SheetContentBanner>
      <div class="SheetView__contentCtn my-6" v-if="actor.description">
        <div class="SheetView__title SheetView__title--divider">
          {{ $t('actorPage.description') }}
        </div>
        <p>{{ actor.description }}</p>
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
          :src="actor.logo?.contentsFilteredUrl?.thumbnail"
          alt=""
          v-if="actor.logo?.contentsFilteredUrl?.thumbnail"
          class="SheetView__logo"
        />
      </div>
      <ChipList :items="actor.thematics" />

      <div class="SheetView__title SheetView__title--divider mt-lg-12">
        <span>{{ $t('actorPage.adminScope') }}</span>
      </div>
      <span>{{ actor.administrativeScopes.map((x) => $t('actors.scope.' + x)).join(', ') }}</span>
      <AdminBoundariesButton :entity="actor" />

      <div class="SheetView__infoCard">
        <div class="d-flex flex-row">
          <v-icon icon="mdi-map-marker-outline" color="main-black" />
          <div class="ml-1">
            <p class="font-weight-bold">{{ actor.officeName }}</p>
            <p>{{ actor.officeAddress }}</p>
          </div>
        </div>
      </div>

      <div class="SheetView__infoCard">
        <div>
          <h5 class="SheetView__title">{{ $t('actorPage.contact') }}</h5>
          <ContactCard
            :name="actor.contactName"
            :description="actor.contactPosition"
            image="https://trustedexecutive.com/wp/wp-content/uploads/2016/06/morpheus-red-pill-blue-pill.jpg"
          />
        </div>
      </div>
    </div>
    <ActorRelatedContent :actor="actor" v-if="appStore.mobile" />
    <div class="SheetView__block SheetView__block--bottom">
      <SectionBanner :text="$t('actorPage.images')" />
      <ImagesMosaic :images="actorImages" :key="mosaicKey" />
      <ContentDivider />
    </div>
  </div>
</template>
<script setup lang="ts">
import type { Actor } from '@/models/interfaces/Actor'
import { useActorsStore } from '@/stores/actorsStore'
import { computed, onMounted, watchEffect, ref } from 'vue'
import { useRoute } from 'vue-router'
import SheetContentBanner from '@/views/_layout/sheet/SheetContentBanner.vue'
import ContentDivider from '@/components/content/ContentDivider.vue'
import ActorRelatedContent from '@/views/actors/components/ActorRelatedContent.vue'
import PrintButton from '@/components/global/PrintButton.vue'
import UpdateInfoLabel from '@/views/_layout/sheet/UpdateInfoLabel.vue'
import ImagesMosaic from '@/components/content/ImagesMosaic.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import ContactCard from '@/components/content/ContactCard.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import { useUserStore } from '@/stores/userStore'
import ChipList from '@/components/content/ChipList.vue'
import AdminBoundariesButton from '@/components/content/adminBoundaries/AdminBoundariesButton.vue'
import { CommentOrigin } from '@/models/interfaces/Comment'

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

onMounted(() => {
  const route = useRoute()
  watchEffect(() => {
    if (actorsStore.dataLoaded) {
      if (actorsStore.selectedActor === null) {
        const actor: Actor | undefined = actorsStore.actors.find(
          (actor) => actor.slug === route.params.slug
        )
        actorsStore.setSelectedActor(actor?.id as string)
      }
    }
  })
})

const isEditable = computed(() => {
  return userStore.userIsAdmin() || actor.value?.createdBy.id === userStore.currentUser?.id
})

function editActor() {
  actorsStore.setActorEditionMode(actor.value)
}
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
