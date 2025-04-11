<template>
  <div class="UpdateInfoLabel">
    <UpdatedAtLabel :date="date" />
    <UpdatedByLabel :user="user" />
  </div>
</template>

<script setup lang="ts">
import type { User } from '@/models/interfaces/auth/User'
import UpdatedAtLabel from '@/views/_layout/sheet/UpdatedAtLabel.vue'
import UpdatedByLabel from '@/views/_layout/sheet/UpdatedByLabel.vue'

defineProps<{
  user: User | null
  date: Date | string
}>()
</script>

<style lang="scss">
.UpdateInfoLabel, .UpdateInfoLabel.show-sm {
  display: flex;
  flex-flow: row wrap;
  gap: 0.25rem;

  > * {
    display: inline;
  }
}
</style>
