<template>
  <div class="ListFilterResetButton">
    <span class="text-body-2">{{ itemsCount }} {{ $t(itemsLabelKey, itemsCount) }}</span>
    <span class="ml-3 cursor-pointer text-caption text-main-blue" @click="() => resetFunction()">{{
      $t('list.resetFilters')
    }}</span>
    <div class="ListView__filteredItemsCount ml-1">{{ filteredItemsCount }}</div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  itemsCount: number
  itemsLabelKey: string
  filteredItemsCount: number
  resetFunction: CallableFunction
}>()
</script>

<style lang="scss">
.ListFilterResetButton {
  display: flex;
  flex-flow: row nowrap;
  align-items: center;
}
</style>
