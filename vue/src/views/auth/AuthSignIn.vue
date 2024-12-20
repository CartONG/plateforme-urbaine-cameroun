<template>
  <AuthDialog class="AuthSignIn">
    <template #title>{{ $t('auth.signIn.title') }}</template>
    <template #content>
      <Form @submit="onSubmit">
        <v-text-field
          v-model="form.email.value.value"
          :label="$t('auth.signIn.form.email')"
          :error-messages="form.email.errorMessage.value"
          @blur="form.email.handleChange"
        />
        <v-text-field
          v-model="form.password.value.value"
          type="password"
          :label="$t('auth.signIn.form.password')"
          :error-messages="form.password.errorMessage.value"
          @blur="form.password.handleChange"
        />
        <router-link
          class="AuthDialog__forgotPassword"
          append
          :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_FORGOT_PASSWORD } }"
        >
          {{ $t('auth.signIn.form.forgotPassword') }}
        </router-link>
        <v-checkbox :label="$t('auth.signIn.form.rememberMe')"></v-checkbox>
        <div class="AuthDialog__error" v-if="userStore.errorWhileSignInOrSignUp">
          {{ $t('auth.signIn.error') }}
        </div>
        <div class="AuthDialog__error text-center" v-if="userStore.invalidAccount">
          {{ $t('auth.signIn.invalidAccount.message') }}
          <v-btn class="mt-4" color="main-green" @click="goToResendActivationEmail()">
            {{ $t('auth.signIn.invalidAccount.resendActivationEmail') }}
          </v-btn>
        </div>
        <v-btn color="main-red" type="submit">{{ $t('auth.signIn.form.submit') }}</v-btn>
      </Form>
    </template>
    <template #bottom-content>
      <span>{{ $t('auth.signIn.question') }}</span>
      <router-link
        append
        :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_BECOME_MEMBER } }"
      >
        {{ $t('auth.becomeMember.title') }}
      </router-link>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'
import Form from '@/components/forms/Form.vue'
import { useUserStore } from '@/stores/userStore'
import { SignInForm } from '@/services/userAndAuth/forms/SignInForm'
import { useRoute, useRouter } from 'vue-router'

const userStore = useUserStore()
const router = useRouter()
const route = useRoute()
const { form, handleSubmit } = SignInForm.getSignInForm()
const onSubmit = handleSubmit(async (values) => {
  await userStore.signIn(values)
})

const goToResendActivationEmail = () => {
  router.replace({ query: { ...route.query, dialog: DialogKey.AUTH_EMAIL_VERIFIER_ASK } })
}
</script>

<style lang="scss">
.AuthDialog {
  &__error {
    flex-direction: column;
  }
}
</style>
