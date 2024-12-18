<template>
  <AuthDialog>
    <template #title>{{ $t('auth.forgotPassword.title') }}</template>
    <template #subtitle>{{ $t('auth.forgotPassword.subtitle') }}</template>
    <template #content>
      <Form @submit.prevent="onSubmit">
        <v-text-field
          v-model="email"
          autocomplete="email"
          :label="$t('auth.forgotPassword.form.email')"
          :error-messages="errorMessage"
          @blur="handleChange"
        />
        <v-btn color="main-red" type="submit" block>{{
          $t('auth.forgotPassword.form.submit')
        }}</v-btn>
      </Form>
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

const router = useRouter()
const route = useRoute()

const validationSchema = toTypedSchema(
  z.object({
    email: UserValidator.emailSchema
  })
)

const { handleSubmit } = useForm({
  validationSchema
})

const {
  value: email,
  errorMessage,
  handleChange
} = useField('email', '', { validateOnValueUpdate: false })

const onSubmit = handleSubmit(async (values) => {
  await AuthenticationService.forgotPassword(values.email)
  await router.replace({
    query: { ...route.query, dialog: DialogKey.AUTH_FORGOT_PASSWORD_OK }
  })
})
</script>
