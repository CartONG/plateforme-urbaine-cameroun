<template>
  <span class="UpdatedAtLabel">{{ $t('labels.updatedAt') }} {{ formattedDate }}</span>
</template>

<script setup lang="ts">
import { localizeDate } from '@/services/utils/UtilsService'
import { computed } from 'vue'

const props = defineProps<{
  date: string | Date
}>()

const formattedDate = computed(() => {
  return localizeDate(props.date, { year: 'numeric', month: 'long', day: 'numeric' })
})
</script>

<style lang="scss">
.UpdatedAtLabel {
  color: rgb(var(--v-theme-main-blue));
  font-size: $font-size-xs;
}
</style>
