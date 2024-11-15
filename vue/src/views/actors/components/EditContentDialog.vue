<template>
  <v-dialog
    v-model="appStore.showEditContentDialog"
    width="auto"
    persistent
  >
    <ActorEditionForm v-if="actorsStore.actorEdition.active"/>
    <AdminUserEditionForm v-if="adminStore.userEdition.active"/>
  </v-dialog>
</template>
<script lang="ts" setup>
import { useApplicationStore } from '@/stores/applicationStore';
import { useActorsStore } from '@/stores/actorsStore';
import ActorEditionForm from '@/views/actors/components/ActorEditionForm.vue';
import AdminUserEditionForm from '@/views/admin/components/admin-members/AdminUserEditionForm.vue';
import { useAdminStore } from '@/stores/adminStore';

const appStore = useApplicationStore();
const actorsStore = useActorsStore();
const adminStore = useAdminStore();
</script>