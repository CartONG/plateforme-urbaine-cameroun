<template>
  <div class="NewSubmission">
    <img loading="lazy" src="@/assets/images/actorToValidate.svg" alt="" />
    <div class="ml-2">
      {{
        $t('admin.form.newSubmission', {
          fullName: createdBy.fullName,
          date: localizeDate(createdAt, { year: 'numeric', month: 'long', day: 'numeric' }),
          time: localizeTime(createdAt, { hour: 'numeric', minute: 'numeric' })
        })
      }}
      <p class="mt-2" v-if="message">
        {{ message }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { User } from '@/models/interfaces/auth/User';
import { localizeDate, localizeTime } from '@/services/utils/UtilsService';

defineProps<{
  createdBy: User
  createdAt: Date
  message?: string
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
