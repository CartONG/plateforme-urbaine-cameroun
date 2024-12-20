<template>
  <AuthDialog>
    <template #title>{{ $t('auth.emailVerifier.title') }}</template>
    <template #content>
      <Form @submit.prevent="onSubmit" v-if="!isSent">
        <v-text-field
          type="email"
          v-model="form.email.value.value"
          :label="$t('auth.becomeMember.form.email')"
          :error-messages="form.email.errorMessage.value"
          @blur="form.email.handleChange"
        />
        <v-btn color="main-red" type="submit" block :disabled="isDisabled">
          {{ $t('dialog.submit') }}
        </v-btn>
      </Form>
      <CheckPoint
        v-if="isSent"
        class="mb-4"
        :label="$t('auth.emailVerifier.subtitle.sent')"
        :highlighted="true"
      />
      <v-btn color="main-red" type="submit" block v-if="isSent" @click="close()">
        {{ $t('dialog.close') }}
      </v-btn>
    </template>
  </AuthDialog>
</template>

<script setup lang="ts">
import AuthDialog from '@/views/auth/AuthDialog.vue'
import { useField, useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import Form from '@/components/forms/Form.vue'
import { UserValidator } from '@/services/userAndAuth/forms/UserValidator'
import { AuthenticationService } from '@/services/userAndAuth/AuthenticationService'
import CheckPoint from '@/components/global/CheckPoint.vue'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import { z } from 'zod'

const router = useRouter()
const isDisabled = ref<boolean>(false)
const isSent = ref<boolean>(false)

const validationSchema = toTypedSchema(
  z.object({
    email: UserValidator.emailSchema
  })
)

const { handleSubmit } = useForm({
  validationSchema
})

const form = {
  email: useField('email', '', { validateOnValueUpdate: false })
}

const onSubmit = handleSubmit(async (values) => {
  try {
    isDisabled.value = true
    await AuthenticationService.resendEmailVerifier(values.email)
    isSent.value = true
  } catch {
    isDisabled.value = false
  }
})

const close = () => router.replace({ query: { dialog: undefined } })
</script>
