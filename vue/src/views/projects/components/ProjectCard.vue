<template>
  <InfoCard
    class="ProjectCard"
    v-if="project != null"
    :to="{ name: 'projectPage', params: { slug: project.slug } }"
    :map="map"
  >
    <template #content>
      <div class="ProjectCard__content">
        <div class="ProjectCard__block ProjectCard__block--left">
          <span class="InfoCard__subTitle">{{ project.geoData.name }}</span>
          <span class="InfoCard__title">{{ project.name }}</span>
          <div class="ProjectCard__infoCtn">
            <span v-if="project.actor?.name">{{ project.actor.name }}</span>
            <span v-if="!map" class="ProjectCard__updatedAt"
              >{{ $t('labels.updatedAt') }} {{ getFormattedDate(project) }}</span
            >
          </div>
        </div>
        <div class="ProjectCard__block ProjectCard__block--right">
          <img class="ProjectCard__logo" :src="project.logo" />
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
      <LikeButton :id="project.id" />
    </template>
    <template #footer-right>
      <v-icon class="InfoCard__actionIcon" icon="mdi mdi-arrow-right" color="light-blue"></v-icon>
    </template>
  </InfoCard>
</template>

<script setup lang="ts">
import type { Project } from '@/models/interfaces/Project'
import InfoCard from '@/components/global/InfoCard.vue'
import ChipList from '@/components/content/ChipList.vue'
import LikeButton from '@/components/global/LikeButton.vue'
import ShareButton from '@/components/global/ShareButton.vue'
import { localizeDate } from '@/services/utils/UtilsService'

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
