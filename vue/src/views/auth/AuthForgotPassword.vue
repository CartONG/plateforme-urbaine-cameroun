<template>
  <AuthDialog>
    <template #title>{{ $t('auth.forgotPassword.title') }}</template>
    <template #subtitle>{{ $t('auth.forgotPassword.subtitle') }}</template>
    <template #content>
      <Form @submit="onSubmit">
        <v-text-field v-model="email" :label="$t('auth.forgotPassword.form.email')" />
        <router-link
          append
          :to="{ query: { ...$route.query, dialog: DialogKey.AUTH_FORGOT_PASSWORD_OK } }"
          class="Link--withoutUnderline"
        >
          <v-btn color="main-red" type="submit" block>{{
            $t('auth.forgotPassword.form.submit')
          }}</v-btn>
        </router-link>
      </Form>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as zod from 'zod'
import Form from '@/components/forms/Form.vue'
import { DialogKey } from '@/models/enums/app/DialogKey'

const validationSchema = toTypedSchema(
  zod.object({
    email: zod
      .string()
      .min(1, { message: 'This is required' })
      .email({ message: 'Must be a valid email' })
  })
)

const { handleSubmit } = useForm({
  validationSchema
})

const { value: email } = useField('email')

const onSubmit = handleSubmit((values) => {
  alert(JSON.stringify(values, null, 2))
})
</script>
