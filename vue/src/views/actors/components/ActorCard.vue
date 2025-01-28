<template>
  <InfoCard class="ActorCard" :to="actorProfileRoute" v-if="actor">
    <template #content>
      <span class="InfoCard__subTitle">{{ actor.acronym }}</span>
      <span class="InfoCard__title">{{ actor.name }}</span>
      <span style="font-size: 14px">{{ actor.category }}</span>
      <div class="ActorCard__logoCtn">
        <img class="ActorCard__logo" :src="actor.logo.contentUrl" v-if="actor.logo" />
      </div>
    </template>
    <template #footer-left>
      <ShareButton :additionnal-path="actor.name" />
      <HighlightButton :item-id="actor.id" />
      <LikeButton :id="actor.id" />
    </template>
    <template #footer-right>
      <v-icon class="InfoCard__actionIcon" icon="mdi mdi-arrow-right" color="light-blue"></v-icon>
    </template>
  </InfoCard>
</template>

<script setup lang="ts">
import type { Actor } from '@/models/interfaces/Actor'
import InfoCard from '@/components/global/InfoCard.vue'
import LikeButton from '@/components/global/LikeButton.vue'
import ShareButton from '@/components/global//ShareButton.vue'
import { computed, type ComputedRef } from 'vue'
import type { RouteLocationAsRelativeGeneric } from 'vue-router'
import HighlightButton from '@/components/global/HighlightButton.vue'
const props = defineProps<{
  actor: Actor
}>()
const actorProfileRoute: ComputedRef<RouteLocationAsRelativeGeneric> = computed(() => ({
  name: 'actorProfile',
  params: {
    slug: props.actor.slug
  }
}))
</script>

<style lang="scss">
.InfoCard.ActorCard {
  .InfoCard__subTitle {
    text-transform: uppercase;
  }

  .ActorCard__logoCtn {
    margin-top: 20px;
    $dim-img: 8rem;
    height: $dim-img;
    width: $dim-img;

    .ActorCard__logo {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
  }
}
</style>
