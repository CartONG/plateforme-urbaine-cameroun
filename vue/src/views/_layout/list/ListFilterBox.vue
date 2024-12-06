<template>
  <div class="ListFilterBox">
    <SectionTitle :title="$t('list.filtersTitle')" />
    <div class="ListFilterBox__filterCtn">
      <slot></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import SectionTitle from '@/components/text-elements/SectionTitle.vue'
</script>

<style lang="scss">
.ListFilterBox {
  display: flex;
  flex-flow: column nowrap;
  width: 100%;
  max-width: 100%;

  .ListFilterBox__filterCtn {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    $dim-filter-gap: 0.5rem;
    gap: $dim-filter-gap;
    margin: 1rem 0;
    max-width: 100%;

    > * {
      max-width: calc($dim-container-w / 4);
      flex: 1 0 calc(25% - $dim-filter-gap * 4);
    }
  }
}
</style>
