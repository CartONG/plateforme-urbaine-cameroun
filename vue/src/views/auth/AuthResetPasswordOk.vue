<template>
  <AuthDialog>
    <template #title>{{ $t('auth.forgotPasswordOk.title') }}</template>
    <template #subtitle>
      <CheckPoint class="mb-4" :label="$t('auth.resetPasswordOk.subtitle')" :highlighted="true" />
    </template>
    <template #content>
      <v-btn color="main-red" @click="closeDialog" block>{{
        $t('auth.resetPasswordOk.form.close')
      }}</v-btn>
    </template>
    <template #bottom-content>
      <router-link append :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_SIGN_IN } }">
        {{ $t('auth.signIn.title') }}
      </router-link>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import CheckPoint from '@/components/global/CheckPoint.vue'
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useRouter } from 'vue-router'
import { DialogKey } from '@/models/enums/app/DialogKey'

const router = useRouter()
const closeDialog = () => router.replace({ query: { dialog: undefined } })
</script>
