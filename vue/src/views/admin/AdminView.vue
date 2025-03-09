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
import AdminPanelsSelector from '@/views/admin/components/AdminPanelsSelector.vue'
import { UserRoles } from '@/models/enums/auth/UserRoles'
import { useUserStore } from '@/stores/userStore'
import { useProjectStore } from '@/stores/projectStore'
import PageBanner from '@/components/banners/PageBanner.vue'
import { onMounted } from 'vue'
import { useCommentStore } from '@/stores/commentStore'
const userStore = useUserStore()
const projectStore = useProjectStore()
const commentStore = useCommentStore()

onMounted(async () => {
  projectStore.getAll()
  commentStore.getAll()
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
