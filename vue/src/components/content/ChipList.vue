<template>
  <div class="ChipList" :align="align">
    <Chip v-for="item in items" :key="item.id" :text="item.name" />
  </div>
</template>

<script setup lang="ts">
import Chip from '@/components/content/Chip.vue'
interface Item {
  id: any
  name: string
  [key: string]: any
}

defineProps<{
  items: Item[]
  align?: string
}>()
</script>
<style lang="scss">
.ChipList {
  display: flex;
  flex-flow: row wrap;
  gap: 0.5rem;

  &[align='left'] {
    justify-content: flex-start;
  }

  &[align='right'] {
    justify-content: flex-end;
  }
}
</style>
