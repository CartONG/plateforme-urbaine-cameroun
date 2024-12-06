<template>
  <div class="ListItems">
    <div class="ListItems__list">
      <slot v-for="item in paginatedItems" name="card" :item="item" class="ListItems__card"></slot>
    </div>
    <Pagination
      class="ListItems__pagination"
      :items="items"
      v-model="paginatedItems"
      :items-per-page="itemsPerPage"
    />
  </div>
</template>

<script setup lang="ts">
import Pagination from '@/components/global/Pagination.vue'
import type { Actor } from '@/models/interfaces/Actor'
import type { Resource } from '@/models/interfaces/Resource'
import { useApplicationStore } from '@/stores/applicationStore'
import { ref, type Ref } from 'vue'
const appStore = useApplicationStore()

defineProps<{
  items: Actor[] | Resource[]
}>()

const itemsPerPage = appStore.mobile ? 5 : 15
const paginatedItems: Ref<Actor[] | Resource[]> = ref([])
</script>

<style lang="scss">
.ListItems {
  display: flex;
  flex-flow: column nowrap;
  gap: 4rem;
  align-items: center;
  .ListItems__list {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.5rem;
    width: 100%;
    .ListItems__card {
      height: 100%;
    }
  }
}

@media (max-width: $bp-lg) {
  .ListItems {
    .ListItems__list {
      grid-template-columns: repeat(1, minmax(0, 1fr));
    }
  }
}
</style>
