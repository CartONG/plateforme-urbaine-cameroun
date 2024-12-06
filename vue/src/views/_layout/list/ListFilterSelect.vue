<template>
  <v-select
    class="ListFilterSelect"
    v-model="model"
    density="compact"
    variant="outlined"
    :label="label"
    :items="items"
    item-title="name"
    item-value="@id"
    multiple
    clearable
  />
</template>

<script setup lang="ts">
import type { ModelRef } from 'vue'

defineProps<{
  items: any[]
  label: string
}>()

const model: ModelRef<string[] | undefined> = defineModel()
</script>
