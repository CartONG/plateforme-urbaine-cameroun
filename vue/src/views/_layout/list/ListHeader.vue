<template>
  <div class="ListHeader">
    <img src="@/assets/images/Mosaic_actors_page.svg" alt="" />
    <div class="ListHeader__content">
      <PageTitle :title="title" />
      <span class="ListHeader__desc">{{ description }}</span>
      <ListSearchBar :placeholder="searchPlaceholder" v-model="searchQuery" />
    </div>
  </div>
</template>

<script setup lang="ts">
import ListSearchBar from '@/views/_layout/list/ListSearchBar.vue'
import PageTitle from '@/components/text-elements/PageTitle.vue'

const searchQuery = defineModel()
defineProps<{
  title: string
  description: string
  searchPlaceholder: string
}>()
</script>

<style lang="scss">
.ListHeader {
  display: flex;
  flex-flow: row-reverse nowrap;
  margin-bottom: 2rem;
  gap: 3rem;
  img {
    max-width: 40%;
  }
  .ListHeader__content {
    margin-top: 3rem;
    display: flex;
    flex-direction: column;
    width: 60%;
    gap: 1.5rem;
  }
}
@media (max-width: $bp-lg) {
  .ListHeader {
    display: flex;
    flex-direction: column;
    width: 100%;
    align-items: center;
    gap: 1.5rem;
    img {
      max-width: 60%;
    }
    .ListHeader__content {
      width: 100%;
      margin-top: 0;
    }
    .ListSearchBar {
      display: none;
    }
  }
}
</style>
