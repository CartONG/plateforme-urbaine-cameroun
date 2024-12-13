<template>
  <AuthDialog>
    <template #title>
      <template v-if="isVerified">
        {{ $t('account.email_verifier.title.success') }}
      </template>
      <template v-if="!isVerified">
        {{ $t('account.email_verifier.title.wrong') }}
      </template>
    </template>
    <template #subtitle>
      <CheckPoint class="mb-4" :label="$t('auth.resetPasswordOk.subtitle')" :highlighted="true" />
    </template>
    <template #content>
      <v-btn color="main-red" @click="closeDialog" block>{{
        $t('auth.resetPasswordOk.form.close')
      }}</v-btn>
    </template>
    <template #bottom-content> </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useRoute, useRouter } from 'vue-router'
import { ref } from 'vue'

enum VerificationStatus {
  SUCCESS,
  FAIL,
  WRONG
}

const router = useRouter()
const route = useRoute()
// get query Params
const query = route.query

const isVerified = ref()

// check if keys are present in query: _hash, email, expiresAt, token
if (!query._hash || !query.email || !query.expiresAt || !query.token) {
  isVerified.value = VerificationStatus.WRONG
  router.replace({ query: { dialog: undefined } })
}

const closeDialog = () => router.replace({ query: { dialog: undefined } })
</script>
