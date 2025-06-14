<template>
  <div
    class="AdminBoundaries__toMap"
    v-if="
      (entity.admin1List && entity.admin1List.length > 0) ||
      (entity.admin3List && entity.admin3List.length > 0)
    "
    @click="showAdminMap"
  >
    <span>{{ $t('adminBoundaries.showInMap') }}</span>
    <v-icon class="ml-2" color="main-green" icon="$arrowRight-circle" size="large"></v-icon>
  </div>
  <AdminBoundariesMap
    v-if="adminBoundariesMapIsVisible"
    :entity="entity"
    @close="adminBoundariesMapIsVisible = false"
  />
</template>

<script setup lang="ts">
import type { Actor } from '@/models/interfaces/Actor';
import type { Project } from '@/models/interfaces/Project';
import { ref } from 'vue';
import AdminBoundariesMap from './AdminBoundariesMap.vue';

defineProps<{
  entity: Project | Actor
}>()

const adminBoundariesMapIsVisible = ref(false)
function showAdminMap() {
  adminBoundariesMapIsVisible.value = true
}
</script>

<style lang="scss" scoped>
.AdminBoundaries__toMap {
  cursor: pointer;
  width: fit-content;
  border: 1px solid rgb(var(--v-theme-main-blue));
  border-radius: 5px;
  font-weight: 500;
  padding: 10px 12px 10px 15px;
}
</style>
