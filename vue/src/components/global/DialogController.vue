<template>
  <component :is="dialogComponent" v-if="dialogComponent != null" :transition="transition" />
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref, watch } from 'vue'
import { VScaleTransition } from 'vuetify/components'
import { useApplicationStore } from '@/stores/applicationStore'
import AuthAskEmailVerifier from '@/views/auth/AuthEmailVerifierSend.vue'
import AuthSignIn from '@/views/auth/AuthSignIn.vue'
import AuthBecomeMember from '@/views/auth/AuthBecomeMember.vue'
import AuthBecomeMemberWhy from '@/views/auth/AuthBecomeMemberWhy.vue'
import AuthBecomeMemberThanks from '@/views/auth/AuthBecomeMemberThanks.vue'
import AuthForgotPassword from '@/views/auth/AuthForgotPassword.vue'
import AuthForgotPasswordOk from '@/views/auth/AuthForgotPasswordOk.vue'
import AuthResetPassword from '@/views/auth/AuthResetPassword.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'
import AuthResetPasswordOk from '@/views/auth/AuthResetPasswordOk.vue'
import AuthEmailVerifier from '@/views/auth/AuthEmailVerifier.vue'
import AuthBecomeMemberRoles from '@/views/auth/AuthBecomeMemberRoles.vue'

const DEFAULT_TRANSITION = VScaleTransition

const appStore = useApplicationStore()
const activeDialog = computed(() => appStore.activeDialog)
const transition: any = ref(DEFAULT_TRANSITION)

watch(activeDialog, (newValue, oldValue) => {
  transition.value = oldValue == null && newValue != null ? DEFAULT_TRANSITION : false
})

onBeforeUnmount(() => {
  transition.value = DEFAULT_TRANSITION
})

const dialogComponent = computed(() => {
  switch (activeDialog.value) {
    case DialogKey.AUTH_SIGN_IN:
      return AuthSignIn
    case DialogKey.AUTH_BECOME_MEMBER:
      return AuthBecomeMember
    case DialogKey.AUTH_BECOME_MEMBER_WHY:
      return AuthBecomeMemberWhy
    case DialogKey.AUTH_BECOME_MEMBER_THANKS:
      return AuthBecomeMemberThanks
    case DialogKey.AUTH_BECOME_MEMBER_ROLES:
      return AuthBecomeMemberRoles
    case DialogKey.AUTH_FORGOT_PASSWORD:
      return AuthForgotPassword
    case DialogKey.AUTH_FORGOT_PASSWORD_OK:
      return AuthForgotPasswordOk
    case DialogKey.AUTH_RESET_PASSWORD:
      return AuthResetPassword
    case DialogKey.AUTH_RESET_PASSWORD_OK:
      return AuthResetPasswordOk
    case DialogKey.AUTH_EMAIL_VERIFIER:
      return AuthEmailVerifier
    case DialogKey.AUTH_EMAIL_VERIFIER_ASK:
      return AuthAskEmailVerifier
    default:
      return null
  }
})
</script>

<style lang="scss">
.v-overlay {
  .v-overlay__scrim {
    &.fade-transition-enter-from,
    &.fade-transition-leave-to {
      opacity: 0.5 !important;
    }
  }
}
</style>
