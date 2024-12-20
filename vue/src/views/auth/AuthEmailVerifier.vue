<template>
  <AuthDialog>
    <template #title>
      {{ $t('auth.emailVerifier.title') }}
    </template>
    <template #subtitle>
      <CheckPoint
        class="mb-4"
        :label="$t('auth.emailVerifier.subtitle.success')"
        :highlighted="true"
        v-if="isVerified === VerificationStatus.SUCCESS"
      />
      <WrongPoint
        class="mb-4"
        :label="$t('auth.emailVerifier.subtitle.wrong')"
        :highlighted="true"
        v-if="isVerified === VerificationStatus.WRONG"
      />
      <WrongPoint
        class="mb-4"
        :label="$t('auth.emailVerifier.subtitle.expired')"
        :highlighted="true"
        v-if="isVerified === VerificationStatus.EXPIRED"
      />
      <WrongPoint
        class="mb-4"
        :label="$t('notifications.common.error.500')"
        :highlighted="true"
        v-if="isVerified === VerificationStatus.FAIL"
      />
    </template>
    <template #content>
      <template v-if="isVerified === VerificationStatus.EXPIRED">
        {{ $t('auth.emailVerifier.content.expired') }}
      </template>
      <v-btn class="mt-8" color="main-red" @click="closeDialog" block>{{
        $t('auth.emailVerifier.close')
      }}</v-btn>
    </template>
    <template #bottom-content />
  </AuthDialog>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useRoute, useRouter } from 'vue-router'
import { onBeforeMount, ref } from 'vue'
import WrongPoint from '@/components/global/WrongPoint.vue'
import { useUserStore } from '@/stores/userStore'
import type { AxiosError } from 'axios'

enum VerificationStatus {
  SUCCESS,
  FAIL,
  WRONG,
  EXPIRED
}

const router = useRouter()
const route = useRoute()

const isVerified = ref<VerificationStatus>()

onBeforeMount(async () => {
  await router.isReady()
  const query = route.query
  if (!query._hash || !query.email || !query.expiresAt || !query.token) {
    isVerified.value = VerificationStatus.WRONG
  } else {
    try {
      await useUserStore().verifyEmail({
        email: query.email as string,
        token: query.token as string,
        expiresAt: query.expiresAt as string,
        _hash: query._hash as string
      })
      isVerified.value = VerificationStatus.SUCCESS
    } catch (error: unknown) {
      const response = (error as AxiosError).response

      if (response && response.status === 410) {
        isVerified.value = VerificationStatus.EXPIRED
      } else if (response && response.status === 400) {
        isVerified.value = VerificationStatus.WRONG
      } else {
        isVerified.value = VerificationStatus.FAIL
      }
    }
  }
})

const closeDialog = () => router.replace({ query: { dialog: undefined } })
</script>
