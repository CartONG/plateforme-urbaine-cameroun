<template>
    <SectionBanner :text="$t('actorPage.projects')"/>
    <div class="SheetView__infoCardCtn">
        <ProjectCard  v-for="project in actorProjects" :key="project.slug" :project="project"/>
    </div>
    <SectionBanner :text="$t('actorPage.data')"/>
    <SectionBanner :text="$t('actorPage.resources')"/>
    <SectionBanner :text="$t('actorPage.services')"/>
</template>

<script setup lang="ts">
import SectionBanner from '@/components/banners/SectionBanner.vue';
import ProjectCard from '@/views/projects/components/ProjectCard.vue';
import type { Actor }  from '@/models/interfaces/Actor';
import { computed } from 'vue';
const props = defineProps<{
    actor: Actor
}>()

const actorProjects = computed(() => {
    return props.actor.projects.map(project => ({ ...project, actor: { name: props.actor.name } }))
})
</script>