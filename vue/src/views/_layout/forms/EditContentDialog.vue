<template>
  <div>
    <ActorForm v-if="actorsStore.actorEdition.active" />
    <AdminUserEditionForm v-if="adminStore.userEdition.active" />
    <ProjectForm
      v-if="projectStore.isProjectFormShown"
      :project="null"
      :isShown="projectStore.isProjectFormShown"
      :type="FormType.CREATE"
      :key="0"
      @close="projectStore.isProjectFormShown = false"
      @submitted="projectStore.isProjectFormShown = false"
    />
    <ResourceForm
      v-if="resourceStore.isResourceFormShown"
      :resource="resourceStore.editedResource ?? null"
      :isShown="resourceStore.isResourceFormShown"
      :type="
        resourceStore.editedResource
          ? resourceStore.editedResource.isValidated
            ? FormType.EDIT
            : FormType.VALIDATE
          : FormType.CREATE
      "
      :key="0"
      @close="resourceStore.isResourceFormShown = false"
      @submitted="resourceStore.isResourceFormShown = false"
    />
    <EditMessageDialog v-if="useApplicationStore().showEditMessageDialog" />
    <EditThanksDialog v-if="useApplicationStore().showEditThanksDialog" />
  </div>
</template>
<script lang="ts" setup>
import { useActorsStore } from '@/stores/actorsStore'
import ActorForm from '@/views/actors/components/ActorForm.vue'
import AdminUserEditionForm from '@/views/admin/components/admin-members/AdminUserEditionForm.vue'
import { useAdminStore } from '@/stores/adminStore'
import { useProjectStore } from '@/stores/projectStore'
import ProjectForm from '@/views/projects/components/ProjectForm.vue'
import { FormType } from '@/models/enums/app/FormType'
import ResourceForm from '@/views/resources/components/ResourceForm.vue'
import { useResourceStore } from '@/stores/resourceStore'
import EditMessageDialog from '@/views/_layout/forms/EditMessageDialog.vue'
import { useApplicationStore } from '@/stores/applicationStore'
import EditThanksDialog from '@/views/_layout/forms/EditThanksDialog.vue'

const actorsStore = useActorsStore()
const projectStore = useProjectStore()
const adminStore = useAdminStore()
const resourceStore = useResourceStore()
</script>
