<template>
  <div class="NewSubmission">
    <img src="@/assets/images/actorToValidate.svg" alt="" />
    <span class="ml-2">{{
      $t('admin.form.newSubmission', {
        fullName: createdBy.fullName,
        date: localizeDate(createdAt, { year: 'numeric', month: 'long', day: 'numeric' }),
        time: localizeTime(createdAt, { hour: 'numeric', minute: 'numeric' })
      })
    }}</span>
  </div>
</template>

<script setup lang="ts">
import type { User } from '@/models/interfaces/auth/User'
import { localizeDate, localizeTime } from '@/services/utils/UtilsService'

defineProps<{
  createdBy: User
  createdAt: Date
}>()
</script>

<style lang="scss">
.NewSubmission {
  background-color: rgb(var(--v-theme-light-yellow));
  padding: 1.5rem;
  gap: 0.25rem;
  border-radius: $dim-radius;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}
</style>
