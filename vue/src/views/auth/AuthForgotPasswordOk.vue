<template>
  <AuthDialog>
    <template #title>{{ $t('auth.forgotPasswordOk.title') }}</template>
    <template #subtitle>
      <CheckPoint class="mb-4" :label="$t('auth.forgotPasswordOk.subtitle')" :highlighted="true" />
    </template>
    <template #content>
      <span class="mb-4 text-center">{{ $t('auth.forgotPasswordOk.form.info') }}</span>
      <v-btn color="main-red" @click="closeDialog" block>{{
        $t('auth.forgotPasswordOk.form.submit')
      }}</v-btn>
    </template>
    <template #bottom-content></template>
  </AuthDialog>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const closeDialog = () => router.replace({ query: { dialog: undefined } })
</script>
