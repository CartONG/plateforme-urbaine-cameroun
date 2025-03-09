<template>
  <div class="ProjectSheetView SheetView" v-if="project != null">
    <div class="SheetView__block SheetView__block--left">
      <div class="SheetView__logoCtn show-sm">
        <img
          v-if="project.logo?.contentUrl"
          :src="project.logo?.contentUrl"
          class="SheetView__logo"
        />
      </div>
      <SheetContentBanner
        page="Project"
        :id="project.id"
        :slug="project.slug"
        :title="project.name"
        :subtitle="project.geoData.name"
        :email="project.focalPointEmail"
        :website="project.website"
        :phone="project.focalPointTel"
        :isEditable="isEditable"
        :updatedAt="project.updatedAt"
        @edit="editProject"
      >
        <template #custom-actions>
          <v-btn
            :to="{ name: 'projects', query: { type: ProjectListDisplay.MAP, project: project.id } }"
            variant="text"
            density="comfortable"
            icon="mdi-map-outline"
            class="hide-sm"
            color="main-blue"
          ></v-btn>
        </template>
      </SheetContentBanner>
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
        <p>{{ project.description }}</p>
      </div>
      <ProjectRelatedContent :project="project" />
    </div>
    <div class="SheetView__block SheetView__block--right">
      <div class="SheetView__updatedAtCtn hide-sm">
        <UpdatedAtLabel :date="project.updatedAt" />
        <PrintButton />
      </div>
      <div class="SheetView__logoCtn hide-sm">
        <img
          v-if="project.logo?.contentUrl"
          :src="project.logo?.contentUrl"
          class="SheetView__logo"
        />
      </div>
      <ChipList :items="project.thematics" />
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
      <div class="SheetView__title SheetView__title--divider">{{ $t('projectPage.partners') }}</div>
      <ImagesMosaic
        :images="project.partners"
        :key="mosaicKey"
        :nb-columns="2"
        :has-viewer="false"
      />
    </div>
    <div class="SheetView__block SheetView__block--bottom">
      <SectionBanner :text="$t('projectPage.inImages')" />
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
import type { Project } from '@/models/interfaces/Project'
import { useProjectStore } from '@/stores/projectStore'
import { computed, onMounted, ref, watch, watchEffect } from 'vue'
import SheetContentBanner from '@/views/_layout/sheet/SheetContentBanner.vue'
import ContentDivider from '@/components/content/ContentDivider.vue'
import { useUserStore } from '@/stores/userStore'
import ChipList from '@/components/content/ChipList.vue'
import ProjectRelatedContent from './components/ProjectRelatedContent.vue'
import { onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import ProjectCard from '@/views/projects/components/ProjectCard.vue'
import ContactCard from '@/components/content/ContactCard.vue'
import ActorCard from '@/views/actors/components/ActorCard.vue'
import PrintButton from '@/components/global/PrintButton.vue'
import UpdatedAtLabel from '@/views/_layout/sheet/UpdatedAtLabel.vue'
import SectionBanner from '@/components/banners/SectionBanner.vue'
import { ProjectListDisplay } from '@/models/enums/app/ProjectListType'
import ProjectForm from '@/views/projects/components/ProjectForm.vue'
import { FormType } from '@/models/enums/app/FormType'
import router from '@/router'
import type { Actor } from '@/models/interfaces/Actor'
import ImagesMosaic from '@/components/content/ImagesMosaic.vue'

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
