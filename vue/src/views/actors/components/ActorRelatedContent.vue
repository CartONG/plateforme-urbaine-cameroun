<template>
  <SectionBanner :text="$t('actorPage.projects')" v-if="actorProjects.length > 0" />
  <div class="SheetView__infoCardCtn">
    <ProjectCard v-for="project in actorProjects" :key="project.slug" :project="project" />
  </div>
</template>

<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue';
import type { Actor } from '@/models/interfaces/Actor';
import ProjectCard from '@/views/projects/components/ProjectCard.vue';
import { computed } from 'vue';
const props = defineProps<{
  actor: Actor
}>()

const actorProjects = computed(() => {
  return props.actor.projects.map((project) => ({ ...project, actor: { name: props.actor.name } }))
})
</script>
