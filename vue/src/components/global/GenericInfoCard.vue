<template>
  <InfoCard class="GenericInfoCard" :to="to" :href="href" :target="href ? '_blank' : undefined">
    <template #content>
      <div class="GenericInfoCard__imgCtn">
        <img
          v-if="image?.contentsFilteredUrl?.thumbnail"
          class="GenericInfoCard__img"
          :src="image.contentsFilteredUrl.thumbnail"
        />
        <slot name="image"></slot>
      </div>
      <div class="GenericInfoCard__infoCtn">
        <slot name="description">
          <span class="InfoCard__title">{{ title }}</span>
          <span class="InfoCard__description">{{ description }}</span>
        </slot>
      </div>
      <div class="GenericInfoCard__chips">
        <v-chip class="mr-2">{{ typeLabel }}</v-chip>
      </div>
    </template>
    <template #footer-left>
      <v-btn
        v-if="mapRoute"
        :to="mapRoute"
        variant="text"
        density="comfortable"
        icon="mdi-map-outline"
        class="hide-sm"
        color="main-blue"
      />
      <ShareButton
        :additionnal-path="additionnalPath as string"
        :external-link="sharedLinkIsExternalToPlatform"
      />
      <HighlightButton :item-id="id" />
      <LikeButton :id="id" />
      <v-btn
        class="GenericInfoCard__editBtn"
        v-if="isEditable"
        variant="text"
        density="comfortable"
        icon="mdi-pencil-outline"
        color="main-blue"
        @click.prevent="editFunction"
      >
      </v-btn>
      <slot name="comment"></slot>
    </template>
    <template #footer-right>
      <v-icon
        class="InfoCard__actionIcon"
        :icon="actionIcon ?? 'mdi-open-in-new'"
        color="light-blue"
      ></v-icon>
    </template>
  </InfoCard>
</template>

<script setup lang="ts">
import InfoCard from '@/components/global/InfoCard.vue'
import LikeButton from '@/components/global/LikeButton.vue'
import ShareButton from '@/components/global/ShareButton.vue'
import HighlightButton from '@/components/global/HighlightButton.vue'
import { ItemType } from '@/models/enums/app/ItemType'
import { computed } from 'vue'
import type { RouteLocationAsRelative } from 'vue-router'
import type { BaseMediaObject } from '@/models/interfaces/object/MediaObject'

const props = defineProps<{
  id: string
  title?: string
  description?: string
  image?: BaseMediaObject
  typeLabel: string
  type?: ItemType
  slug?: string
  actionIcon?: string
  isEditable?: boolean
  editFunction?: () => void
  mapRoute?: RouteLocationAsRelative | null
  href?: string
}>()

const to = computed(() => {
  switch (props.type) {
    case ItemType.PROJECT:
      return { name: 'projectPage', params: { slug: props.slug } }
    case ItemType.ACTOR:
      return { name: 'actorProfile', params: { slug: props.slug } }
    default:
      return undefined
  }
})

const sharedLinkIsExternalToPlatform = ItemType.RESOURCE === props.type
const additionnalPath = computed(() => {
  switch (props.type) {
    case ItemType.PROJECT:
      return 'projects/' + props.slug
    case ItemType.ACTOR:
      return 'actors/' + props.slug
    case ItemType.RESOURCE:
      return props.href
    default:
      return ''
  }
})
</script>

<style lang="scss">
.GenericInfoCard {
  padding: 0 !important;
  $dim-img-h: 13rem;
  $dim-text-max-h: 3rem;
  $dim-card-h: 26rem;
  $card-transition: all 0.15s ease-in;
  height: $dim-card-h;
  max-height: $dim-card-h;
  min-height: $dim-card-h;
  justify-content: space-between;

  &:hover {
    .GenericInfoCard__imgCtn {
      height: 0;
      min-height: 0;
      opacity: 0;
    }
    .GenericInfoCard__infoCtn {
      margin-top: 0;

      .InfoCard__description {
        max-height: calc($dim-text-max-h + $dim-img-h);
      }
    }
    .GenericInfoCard__editBtn {
      opacity: 1;
    }
  }
  .GenericInfoCard__editBtn {
    opacity: 0;
    transition: all 0.15s ease-in;
  }

  .GenericInfoCard__imgCtn {
    position: absolute;
    transition: $card-transition;
    height: $dim-img-h;
    inset: 0;
    min-height: $dim-img-h;
    width: 100%;
    background: linear-gradient(
      to top,
      rgb(var(--v-theme-light-yellow)) 0%,
      rgba(var(--v-theme-light-yellow), 15%) 100%
    );

    .GenericInfoCard__img {
      $dim-margin: 1rem;
      width: 100%;
      height: 100%;
      min-height: calc($dim-img-h);
      object-fit: cover;
      mix-blend-mode: multiply;

      &[empty='true'] {
        padding: $dim-margin;
        object-fit: contain;
        mix-blend-mode: multiply;
      }
    }
  }
  .GenericInfoCard__chips {
    display: flex;
    flex-flow: row wrap;
    padding: 0.25rem 1.25rem !important;
  }
  .InfoCard__footer {
    margin: 1rem 1.25rem !important;
  }
  .GenericInfoCard__infoCtn {
    transition: margin-top 0.15s ease-in;
    padding: 1rem 1.25rem !important;
    margin-top: $dim-img-h;
    display: flex;
    flex-flow: column nowrap;
    gap: 0.5rem;
    background: white;
    overflow-y: hidden;
    position: relative;

    .InfoCard__title {
      font-size: $font-size-h5 !important;
    }
    .InfoCard__description {
      max-height: $dim-text-max-h;
      font-size: $font-size-sm;
      transition: $card-transition;
    }

    &::after {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      background: linear-gradient(to bottom, transparent calc(100% - 2rem), white 100%);
    }
  }
}
</style>
