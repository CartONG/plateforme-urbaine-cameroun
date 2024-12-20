<template>
  <AuthDialog class="AuthBecomeMemberThanks">
    <template #title>{{ $t('auth.becomeMemberThanks.title') }}</template>
    <template #content>
      <CheckPoint
        class="mb-4"
        :label="$t('auth.becomeMemberThanks.subtitle')"
        :highlighted="true"
      />
      <span class="mb-4 text-center">{{ $t('auth.becomeMemberThanks.info') }}</span>
      <v-btn color="main-red" @click="closeDialog" block>
        {{ $t('dialog.close') }}
      </v-btn>
    </template>
    <template #bottom-content />
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue'
import CheckPoint from '@/components/global/CheckPoint.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const closeDialog = () => router.replace({ query: { dialog: undefined } })
</script>

<style lang="scss">
.AuthBecomeMemberThanks {
  .v-checkbox .v-selection-control {
    min-height: 0 !important;
  }
}
</style>
