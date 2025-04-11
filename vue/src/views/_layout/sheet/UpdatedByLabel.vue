<template>
  <span class="UpdatedByLabel" v-if="authorName"
    >{{ $t('labels.updatedBy') }} {{ authorName }}</span
  >
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { User } from '@/models/interfaces/auth/User'

const props = defineProps<{
  user: User | null
}>()

const authorName = computed(() => {
  return props.user?.fullName
})
</script>

<style lang="scss">
.UpdatedByLabel {
  color: rgb(var(--v-theme-main-blue));
  font-size: $font-size-xs;
}
</style>
