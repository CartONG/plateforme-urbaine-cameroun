<template>
  <div class="ProjectSheetView SheetView" v-if="project != null">
    <div class="SheetView__block SheetView__block--left">
      <div class="SheetView__logoCtn show-sm">
        <img
          loading="lazy"
          v-if="project.logo?.contentUrl"
          :src="project.logo?.contentUrl"
          class="SheetView__logo"
        />
      </div>
      <SheetContentBanner
        :page="CommentOrigin.PROJECT"
        :id="project.id"
        :slug="project.slug"
        :title="project.name"
        :subtitle="project.geoData?.name"
        :email="project.focalPointEmail"
        :website="project.website"
        :phone="project.focalPointTel"
        :isEditable="isEditable"
        :updatedAt="project.updatedAt"
        :map-route="{
          name: 'projects',
          query: { type: ProjectListDisplay.MAP, project: project.id }
        }"
        :map-btn-tooltip="$t('projectPage.seeLocation')"
        :createdBy="project.createdBy"
        @edit="editProject"
      />
      <ProjectForm
        v-if="isEditable"
        :type="FormType.EDIT"
        :project="project"
        :isShown="isFormShown"
        @close="isFormShown = false"
        @submitted="(project) => updateProject(project)"
      />
      <div class="SheetView__contentCtn my-6">
        <div class="SheetView__title SheetView__title--divider">{{ $t('projectPage.about') }}</div>
        <span v-html="formattedDescription"></span>
      </div>
      <ProjectRelatedContent :project="project" />
    </div>
    <div class="SheetView__block SheetView__block--right">
      <div class="SheetView__updatedAtCtn hide-sm">
        <UpdateInfoLabel :date="project.updatedAt" :user="project.createdBy" />
        <PrintButton />
      </div>
      <div class="SheetView__logoCtn hide-sm">
        <img
          loading="lazy"
          v-if="project.logo?.contentUrl"
          :src="project.logo?.contentUrl"
          class="SheetView__logo"
        />
      </div>
      <ChipList :items="project.thematics" />

      <div v-if="project.odds && Array.isArray(project.odds) && project.odds.length > 0">
        <div class="SheetView__title SheetView__title--divider">
          <span>{{ $t('forms.odds.title') }}</span>
        </div>
        <div>
          <template
            v-for="odd in project.odds?.sort((a, b) => parseInt(a) - parseInt(b))"
            :key="odd"
          >
            <img
              class="SheetView__ODD mr-2"
              :src="`/img/odd/F-WEB-Goal-${odd}.webp`"
              :alt="odd"
              v-if="parseInt(odd) >= 1 && parseInt(odd) <= 17"
            />
            <span v-else>{{ $t('forms.odds.' + odd) }}</span>
          </template>
        </div>
      </div>

      <div>
        <div class="SheetView__title SheetView__title--divider">
          <span>{{ $t('actorPage.adminScope') }}</span>
        </div>
        <span>{{
          project.administrativeScopes.map((x) => $t('actors.scope.' + x)).join(', ')
        }}</span>
        <AdminBoundariesButton :entity="project" />
      </div>

      <div>
        <div
          class="SheetView__title SheetView__title--divider"
          v-if="project.banoc || project.banocUrl"
        >
          <span>{{ $t('forms.banoc.title') }}</span>
        </div>
        <div class="d-flex flex-column">
          <p v-if="project.banoc">
            <span class="font-weight-bold">{{ $t('forms.banoc.code') + ':' }}</span>
            {{ project.banoc }}
          </p>
          <p v-if="project.banocUrl">
            <span class="font-weight-bold">{{ $t('forms.banoc.url') + ':' }}</span>
            <a :href="normalizeUrl(project.banocUrl)" target="_blank">{{ project.banocUrl }}</a>
          </p>
        </div>
      </div>

      <div class="SheetView__infoCard">
        <div class="SheetView__infoCardBlock">
          <h5 class="SheetView__title">{{ $t('projectPage.projectOwner') }}</h5>
          <ActorCard :actor="project.actor as Actor" light="true" />
        </div>
        <div class="SheetView__infoCardBlock">
          <h5 class="SheetView__title">{{ $t('projectPage.focalPoint') }}</h5>
          <ContactCard
            :name="project.focalPointName"
            :description="project.focalPointPosition"
            :image="project.focalPointPhoto"
          />
        </div>
      </div>
      <div class="SheetView__title SheetView__title--divider" v-if="project.partners.length">
        {{ $t('projectPage.partners') }}
      </div>
      <ImagesMosaic
        :images="project.partners"
        :key="mosaicKey"
        :nb-columns="2"
        :has-viewer="false"
      />
    </div>
    <div class="SheetView__block SheetView__block--bottom">
      <SectionBanner :text="$t('projectPage.inImages')" v-if="images.length" />
      <ImagesMosaic :images="images" :key="mosaicKey" />
      <ContentDivider />
      <SectionBanner
        :text="$t('projectPage.otherProjectsWithSameThematics')"
        :hideHalfCircle="true"
      />
      <div class="SheetView__infoCardCtn">
        <ProjectCard v-for="project in similarProjects" :key="project.id" :project="project" />
      </div>
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
import { FormType } from '@/models/enums/app/FormType'
import { ProjectListDisplay } from '@/models/enums/app/ProjectListType'
import type { Actor } from '@/models/interfaces/Actor'
import { CommentOrigin } from '@/models/interfaces/Comment'
import type { Project } from '@/models/interfaces/Project'
import router from '@/router'
import { formatHTMLForSheetView, normalizeUrl } from '@/services/utils/UtilsService'
import { useApplicationStore } from '@/stores/applicationStore'
import { useProjectStore } from '@/stores/projectStore'
import { useUserStore } from '@/stores/userStore'
import SheetContentBanner from '@/views/_layout/sheet/SheetContentBanner.vue'
import UpdateInfoLabel from '@/views/_layout/sheet/UpdateInfoLabel.vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import ProjectCard from '@/views/projects/components/ProjectCard.vue'
import ProjectForm from '@/views/projects/components/ProjectForm.vue'
import { computed, onMounted, ref, watch, watchEffect } from 'vue'
import { onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import ProjectRelatedContent from './components/ProjectRelatedContent.vue'

const userStore = useUserStore()
const projectStore = useProjectStore()
const project = computed(() => projectStore.project)
const isFormShown = ref(false)
const mosaicKey = ref(0)

const images = computed(() => {
  const images = project.value?.images ?? []
  const externalImages = project.value?.externalImages ?? []
  return [...images, ...externalImages]
})

onBeforeRouteUpdate(async (to) => {
  if (
    (projectStore.project?.slug && projectStore.project.slug !== to.params.slug) ||
    typeof to.params.slug === 'string'
  ) {
    await projectStore.loadProjectBySlug(to.params.slug)
  }
})

onBeforeRouteLeave(() => {
  projectStore.project = null
  projectStore.resetFilters()
})

onMounted(async () => {
  await loadSimilarProjects()
  useApplicationStore().isLoading = false
})

watch(
  () => projectStore.project,
  async () => await loadSimilarProjects()
)

watchEffect(() => {
  if (images.value) {
    mosaicKey.value++
  }
})

const loadSimilarProjects = async () => {
  if (projectStore.project != null) await projectStore.loadSimilarProjects()
}

const updateProject = (project: Project) => {
  projectStore.project = project
  isFormShown.value = false
  router.push({ name: 'projectPage', params: { slug: projectStore.project?.slug } })
}

const similarProjects = computed(() => projectStore.similarProjects)
const isEditable = computed(() => {
  return (
    userStore.userIsAdmin() ||
    (projectStore.project?.createdBy?.id === userStore.currentUser?.id &&
      userStore.currentUser?.id != null)
  )
})

const editProject = () => {
  isFormShown.value = true
}

const formattedDescription = computed(() =>
  formatHTMLForSheetView(project.value?.description as string)
)
</script>

<style lang="scss">
@import '@/assets/styles/views/SheetView';

.ProjectSheetView {
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
  .ProjectSheetView {
    .SheetView__block--bottom {
      display: none;
    }
  }
}
</style>
