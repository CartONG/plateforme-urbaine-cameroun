<template>
  <div class="AdminPanel">
    <AdminTopBar
      page="Projects"
      :items="projectStore.projects"
      :sortingListItems="sortOptions"
      :createFunction="showProjectForm"
      searchKey="name"
      @updateSortingKey="(e) => (sortingProjectsSelectedMethod = e)"
      @create="createProject()"
      @update-search-query="(e) => (searchQuery = e)"
    />
    <ProjectForm
      :type="formType"
      :project="projectSubmission"
      :isShown="isFormShown"
      :key="projectSubmission?.id ?? -1"
      @close="closeForm"
      @submitted="closeForm"
    />
    <AdminTable
      :items="sortedProjects"
      :tableKeys="['name', 'actor.acronym', 'updatedAt']"
      :column-widths="['auto', '15%', '15%', '15%']"
    >
      <template #editContentCell="{ item }">
        <template v-if="!item.isValidated">
          <v-btn
            size="small"
            icon="mdi-arrow-right"
            class="text-main-blue"
            @click="editProject(item as Project, FormType.VALIDATE)"
          ></v-btn>
        </template>
        <template v-else>
          <v-btn
            density="comfortable"
            icon="mdi-pencil-outline"
            @click="editProject(item as Project)"
            class="mr-2"
          ></v-btn>
          <v-btn density="comfortable" icon="mdi-dots-vertical">
            <v-icon icon="mdi-dots-vertical"></v-icon>
            <v-menu activator="parent" location="left">
              <v-list class="AdminPanel__additionnalMenu">
                <v-list-item
                  :to="{ name: 'projectPage', params: { slug: (item as Project).slug } }"
                  >{{ $t('projects.admin.goToPage') }}</v-list-item
                >
                <v-list-item @click="projectStore.deleteProject(item as Project)">{{
                  $t('projects.admin.delete')
                }}</v-list-item>
              </v-list>
            </v-menu>
          </v-btn>
        </template>
      </template>
    </AdminTable>
  </div>
</template>
<script setup lang="ts">
import type { Project } from '@/models/interfaces/Project'
import { useProjectStore } from '@/stores/projectStore'
import { computed, onBeforeMount, ref, type Ref } from 'vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import AdminTable from '@/components/admin/AdminTable.vue'
import { i18n } from '@/plugins/i18n'
import ProjectForm from '@/views/projects/components/ProjectForm.vue'
import { FormType } from '@/models/enums/app/FormType'
import { ProjectService } from '@/services/projects/ProjectService'

const projectStore = useProjectStore()
const sortingProjectsSelectedMethod = ref('isValidated')
const isFormShown = ref(false)
const projects = computed(() => projectStore.projects)
const formType = ref(FormType.CREATE)
const projectSubmission: Ref<Project | null> = ref(null)

onBeforeMount(async () => await projectStore.getAll())

const sortOptions = [
  {
    sortingKey: 'isValidated',
    text: i18n.t('projects.admin.sort.options.projectToValidate')
  },
  {
    sortingKey: 'name',
    text: i18n.t('projects.admin.sort.options.projectName')
  }
]
const showProjectForm = () => {
  isFormShown.value = true
}

const createProject = () => {
  formType.value = FormType.CREATE
  projectSubmission.value = null
  showProjectForm()
}

const editProject = async (project: Project, type: FormType = FormType.EDIT) => {
  formType.value = type
  projectSubmission.value = await ProjectService.get(project)
  showProjectForm()
}

const closeForm = () => {
  projectSubmission.value = null
  isFormShown.value = false
}

const sortedProjects = computed(() => {
  switch (sortingProjectsSelectedMethod.value) {
    case 'isValidated':
      return filteredProjects.value.slice().sort((a: Project, b: Project) => {
        if (a.isValidated !== b.isValidated) {
          return Number(a.isValidated) - Number(b.isValidated)
        }
        return a.name.localeCompare(b.name)
      })
    case 'name':
      return filteredProjects.value.slice().sort((a: Project, b: Project) => {
        return a.name.localeCompare(b.name)
      })
    default:
      return filteredProjects.value
  }
})

const searchQuery = ref('')
const filteredProjects = computed(() => {
  if (!searchQuery.value) {
    return projects.value
  }
  return projects.value.filter((item: Project) => {
    return (
      (item.actor.acronym
        ? item.actor.acronym.toLowerCase().includes(searchQuery.value.toLowerCase())
        : false) || item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  })
})
</script>
