<template>
  <AuthDialog>
    <template #title>{{ $t('auth.resetPassword.title') }}</template>
    <template #subtitle v-if="invalidToken !== undefined && !invalidToken">
      {{ $t('auth.resetPassword.subtitle') }}
    </template>
    <template #content>
      <Form @submit.prevent="onSubmit" v-if="invalidToken !== undefined && !invalidToken">
        <v-text-field
          type="password"
          autocomplete="new-password"
          v-model="form.plainPassword.value.value"
          :label="$t('auth.resetPassword.form.password')"
          :error-messages="form.plainPassword.errorMessage.value"
          @blur="form.plainPassword.handleChange"
        />
        <v-text-field
          type="password"
          autocomplete="new-password"
          v-model="form.confirmPassword.value.value"
          :label="$t('auth.resetPassword.form.confirmPassword')"
          :error-messages="form.confirmPassword.errorMessage.value"
          @blur="form.confirmPassword.handleChange"
        />
        <v-btn color="main-red" type="submit" block>
          {{ $t('auth.resetPassword.form.submit') }}
        </v-btn>
      </Form>
      <div
        v-else-if="invalidToken !== undefined && invalidToken"
        class="text-center dark:color-warning-200500"
      >
        <span>{{ $t('auth.resetPassword.invalidToken') }}</span>
      </div>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'
import Form from '@/components/forms/Form.vue'
import { UserValidator } from '@/services/userAndAuth/forms/UserValidator'
import { AuthenticationService } from '@/services/userAndAuth/AuthenticationService'
import { useRoute, useRouter } from 'vue-router'
import { DialogKey } from '@/models/enums/app/DialogKey'
import { computed, onBeforeMount, ref } from 'vue'

const router = useRouter()
const route = useRoute()

const invalidToken = ref<boolean>()
const token = computed(() => route.query.token as string | null)

const validationSchema = toTypedSchema(
  UserValidator.refinePasswordMatch(z.object(UserValidator.passwordsObject()))
)

const { handleSubmit } = useForm({
  validationSchema
})

const form = {
  plainPassword: useField('plainPassword', '', { validateOnValueUpdate: false }),
  confirmPassword: useField('confirmPassword', '', { validateOnValueUpdate: false })
}

const onSubmit = handleSubmit(async (values) => {
  if (token.value) {
    await AuthenticationService.resetPassword(token.value, values.plainPassword)
    await router.replace({
      query: { dialog: DialogKey.AUTH_RESET_PASSWORD_OK }
    })
  }
})

onBeforeMount(async () => {
  await router.isReady()
  try {
    if (token.value) {
      await AuthenticationService.checkResetPasswordToken(token.value)
      invalidToken.value = false
    } else {
      invalidToken.value = true
    }
  } catch {
    invalidToken.value = true
  }
})
</script>
