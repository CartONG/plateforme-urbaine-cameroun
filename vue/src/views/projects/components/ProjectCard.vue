<template>
  <InfoCard
    class="ProjectCard"
    v-if="project != null"
    @click="applicationStore.isLoading = true"
    :to="{ name: 'projectPage', params: { slug: project.slug } }"
    :map="map"
  >
    <template #content>
      <div class="ProjectCard__content">
        <div class="ProjectCard__block ProjectCard__block--left">
          <span class="InfoCard__subTitle" v-if="project.geoData">{{ project.geoData.name }}</span>
          <span class="InfoCard__title">{{ project.name }}</span>
          <div class="ProjectCard__infoCtn">
            <span v-if="project.actor">{{ project.actor.name }}</span>
            <span v-if="!map" class="ProjectCard__updatedAt"
              >{{ $t('labels.updatedAt') }} {{ getFormattedDate(project) }}</span
            >
          </div>
        </div>
        <div class="ProjectCard__block ProjectCard__block--right">
          <img
            loading="lazy"
            v-if="project.logo?.contentsFilteredUrl.thumbnail"
            class="ProjectCard__logo"
            :src="project.logo?.contentsFilteredUrl.thumbnail"
          />
        </div>
      </div>
      <ChipList
        v-if="!map"
        class="ProjectCard__thematicsCtn"
        :items="project.thematics"
        :align="'right'"
      />
    </template>
    <template #footer-left>
      <ShareButton :additionnal-path="'/' + project.slug" />
      <HighlightButton :item-id="project.id" />
      <LikeButton :id="project.id" />
      <CommentButton position="Card" :origin="CommentOrigin.PROJECT" :originSlug="project.slug" />
    </template>
    <template #footer-right>
      <v-icon class="InfoCard__actionIcon" icon="$arrowRight" color="light-blue"></v-icon>
    </template>
  </InfoCard>
</template>

<script setup lang="ts">
import CommentButton from '@/components/comments/CommentButton.vue'
import ChipList from '@/components/content/ChipList.vue'
import HighlightButton from '@/components/global/HighlightButton.vue'
import InfoCard from '@/components/global/InfoCard.vue'
import LikeButton from '@/components/global/LikeButton.vue'
import ShareButton from '@/components/global/ShareButton.vue'
import { CommentOrigin } from '@/models/interfaces/Comment'
import type { Project } from '@/models/interfaces/Project'
import { localizeDate } from '@/services/utils/UtilsService'
import { useApplicationStore } from '@/stores/applicationStore'
const applicationStore = useApplicationStore()

defineProps<{
  project: Project | null
  map?: boolean
}>()

const getFormattedDate = (project: Project) => {
  return localizeDate(project.updatedAt, { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>

<style lang="scss">
.ProjectCard {
  &[map='true'] {
    width: 15rem;
    padding-bottom: 1rem;

    .ProjectCard__content {
      flex-flow: column nowrap;
      gap: 0.5rem;

      .ProjectCard__block {
        &--left {
          gap: 0.25rem;
        }
        &--right {
          height: 70px;
          width: 70px;
        }
      }
    }

    .InfoCard__footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 0;
    }
  }

  &--light {
    .ProjectCard__thematicsCtn,
    .ProjectCard__updatedAt {
      display: none;
    }
  }
  &[show='false'] {
    opacity: 0;
  }

  .ProjectCard__content {
    display: flex;
    flex-flow: row nowrap;
    margin: 0.5rem 0 1rem 0;
    justify-content: space-between;

    .ProjectCard__block {
      &--left {
        display: flex;
        flex-flow: column nowrap;
        gap: 0.5rem;

        .ProjectCard__infoCtn {
          display: flex;
          flex-flow: column nowrap;
          gap: 0.5rem;

          .ProjectCard__updatedAt {
            font-size: $font-size-xs;
          }
        }
      }

      &--right {
        height: 90px;
        width: 140px;

        .ProjectCard__logo {
          max-width: 100%;
          height: 100%;
          object-fit: cover;
        }
      }
    }
  }
}
</style>
