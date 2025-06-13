<template>
  <div class="AdminView">
    <PageBanner
      :title="$t('admin.title')"
      class="mt-10"
      v-if="userStore.currentUser?.roles.includes(UserRoles.ADMIN)"
    />
    <div
      class="AdminView__content mt-10"
      v-if="userStore.currentUser?.roles.includes(UserRoles.ADMIN)"
    >
      <AdminPanelsSelector class="AdminView__tab" />
      <router-view />
    </div>
    <div v-else>Access denied</div>
  </div>
</template>
<script setup lang="ts">
import PageBanner from '@/components/banners/PageBanner.vue'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import { useActorsStore } from '@/stores/actorsStore'
import { useAdminStore } from '@/stores/adminStore'
import { useApplicationStore } from '@/stores/applicationStore'
import { useAtlasStore } from '@/stores/atlasStore'
import { useCommentStore } from '@/stores/commentStore'
import { useHighlightStore } from '@/stores/highlightStore'
import { useProjectStore } from '@/stores/projectStore'
import { useQgisMapStore } from '@/stores/qgisMapStore'
import { useResourceStore } from '@/stores/resourceStore'
import { useUserStore } from '@/stores/userStore'
import AdminPanelsSelector from '@/views/admin/components/AdminPanelsSelector.vue'
import { onMounted } from 'vue'
const userStore = useUserStore()
const projectStore = useProjectStore()
const actorsStore = useActorsStore()
const applicationStore = useApplicationStore()
const commentStore = useCommentStore()
const atlasStore = useAtlasStore()
const highlightStore = useHighlightStore()
const qgisMapStore = useQgisMapStore()
const resourceStore = useResourceStore()
const adminStore = useAdminStore()

onMounted(async () => {
  const promises = [
    actorsStore.getActors(),
    projectStore.getAll(),
    commentStore.getAll(),
    atlasStore.getAll(),
    highlightStore.getAll(),
    qgisMapStore.getAll(),
    resourceStore.getAll(),
    adminStore.getMembers()
  ]
  await Promise.all(promises)
  applicationStore.isLoading = false
})
</script>
<style lang="scss">
.AdminView {
  display: flex;
  flex-flow: column nowrap;
  width: 100%;

  .AdminView__content {
    display: flex;
    flex-flow: row nowrap;
    gap: 2rem;

    .AdminView__tab {
      width: 25rem;
    }
    .AdminView__Right {
      background-color: white;
    }
    .AdminPanel {
      display: flex;
      flex-direction: column;
      width: 100%;
      &__additionnalMenu {
        font-weight: 700;
        color: rgb(var(--v-theme-main-blue));
      }
    }
  }
}
</style>
